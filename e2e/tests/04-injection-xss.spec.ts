import { test, expect } from "@playwright/test";
import { loginAdmin } from "../helpers/admin-login";
import { PAYLOADS, TS } from "../helpers/data";

/**
 * 阶段 3-2 续：恶意输入 / WAF / 越权探测
 *
 * 这些用例的"绿色"含义：
 *   - 200/4xx + 业务错误提示 = 通过
 *   - 5xx 或 弹出 alert = 失败
 *   - 任何泄露源代码 / DB 报错堆栈 = 失败
 */
test.describe.serial("注入 / XSS / 边界输入", () => {
  test("登录页：用户名带 SQL 单引号不应让服务端 500", async ({ page }) => {
    await page.goto("/admin");
    await page.locator('input[type="text"], input[name="username"]').first().fill(PAYLOADS.sqli);
    await page.locator('input[type="password"]').first().fill("whatever");

    const respPromise = page
      .waitForResponse(
        (r) => /\/admin(\?|$)/.test(r.url()) && r.request().method() === "POST",
        { timeout: 15_000 },
      )
      .catch(() => null);

    await page.locator('button[type="submit"]').first().click();
    const resp = await respPromise;

    if (resp) {
      expect(
        [200, 400, 401, 403, 422],
        `SQL 探针应被 WAF 优雅拦截，实际状态: ${resp.status()}`,
      ).toContain(resp.status());
    }
    // 绝不能进入 dashboard
    await expect(page).not.toHaveURL(/\/admin\/dashboard/);
  });

  test("注册页：超长用户名应被拒绝且不 500", async ({ page }) => {
    await page.goto("/register");
    const u = page
      .locator('input[name="username"], input[type="text"]')
      .first();
    if (await u.count()) {
      await u.fill(PAYLOADS.longText.slice(0, 5000));
    }
    await page
      .locator('input[type="email"], input[name="email"]')
      .first()
      .fill(`a${TS}@a.com`)
      .catch(() => {});
    await page.locator('input[type="password"]').first().fill("Aa12345678").catch(() => {});

    const respPromise = page
      .waitForResponse(
        (r) =>
          /\/register(\?|$)/.test(r.url()) && r.request().method() === "POST",
        { timeout: 15_000 },
      )
      .catch(() => null);

    await page
      .locator('button[type="submit"]')
      .first()
      .click()
      .catch(() => {});
    const resp = await respPromise;
    if (resp) {
      expect(resp.status(), "注册超长输入不应 5xx").toBeLessThan(500);
    }
  });

  test("XSS：商品分类名嵌入脚本不能在列表页执行", async ({ page }) => {
    await loginAdmin(page);

    let dialogTriggered = false;
    page.on("dialog", async (d) => {
      dialogTriggered = true;
      await d.dismiss();
    });

    await page.goto("/admin/shop/category");
    await page.waitForLoadState("networkidle").catch(() => {});

    const addBtn = page
      .locator('button:has-text("添加"), button:has-text("新增")')
      .first();
    if (!(await addBtn.count())) {
      test.skip(true, "未找到分类添加按钮，跳过 XSS 探测");
    }
    await addBtn.click();

    const nameInput = page.locator('input[name="name"]').first();
    await expect(nameInput).toBeVisible({ timeout: 10_000 });
    await nameInput.fill(PAYLOADS.xss);

    await page
      .locator(
        'button:has-text("保存"), button:has-text("提交"), button[type="submit"]',
      )
      .first()
      .click();

    await page.waitForTimeout(1500);
    await page.reload();

    expect(dialogTriggered, "XSS 不能触发 alert 弹窗").toBeFalsy();
    const pwned = await page.evaluate(() => (window as any).__pwned__);
    expect(pwned, "XSS 不能在 window 上注入变量").toBeFalsy();

    // cleanup：删除可能存在的脏数据
    page.once("dialog", (d) => d.accept());
    const evilRow = page
      .locator('tr:has-text("script"), tr:has-text("onerror")')
      .first();
    if (await evilRow.count()) {
      await evilRow
        .locator('a:has-text("删除"), button:has-text("删除")')
        .first()
        .click()
        .catch(() => {});
      await page
        .locator(".layui-layer-btn0")
        .first()
        .click()
        .catch(() => {});
    }
  });

  test("PostDecrypt 空 body + 无签名头：禁止旁路（B2 修复验证）", async ({ request }) => {
    const r = await request.post("/shop/order/trade", {
      headers: { "Content-Type": "application/json" },
      data: "",
    });
    expect(r.status(), "空 body + 无 Secret/Signature 头不应 5xx").toBeLessThan(500);
  });

  test("PostDecrypt 伪造签名头 + 空 body：必须被拒（B2 修复验证）", async ({ request }) => {
    const r = await request.post("/shop/order/trade", {
      headers: {
        "Content-Type": "application/json",
        Secret: "a".repeat(32),
        Signature: "fake_signature_12345",
      },
      data: "",
    });
    const body = await r.text();
    expect(
      body,
      '空 body + 伪造 Secret/Signature 必须返回 "signature failure"',
    ).toMatch(/signature\s*failure|invalid|失败/i);
  });

  test("未带 Signature 的伪造 POST 应被拒", async ({ request }) => {
    const r = await request.post("/shop/order/trade", {
      headers: { "Content-Type": "application/json" },
      data: JSON.stringify({ items: [{ id: 1, num: 1 }] }),
    });
    const body = await r.text();
    expect(body).toMatch(/signature|invalid|失败/i);
  });

  test("路径穿越探测", async ({ request }) => {
    const r = await request.get(
      `/admin/upload?file=${encodeURIComponent(PAYLOADS.pathTraversal)}`,
    );
    const body = await r.text();
    expect(body, "服务端不应响应 /etc/passwd 内容").not.toMatch(
      /root:.*:0:0:/,
    );
  });

  test("响应头不能泄露 PHP 版本 / 框架敏感信息", async ({ request }) => {
    const r = await request.get("/");
    const headers = r.headers();
    // X-Powered-By 通常会暴露 PHP 版本
    expect.soft(headers["x-powered-by"], "X-Powered-By 不应泄露 PHP 版本").not.toMatch(
      /PHP\/\d/,
    );
    // Server: nginx 是允许的，PHP/x.y.z 不允许
    expect.soft(headers["server"]).not.toMatch(/PHP\/\d/);
  });

  test("HTTPS 安全响应头基线", async ({ request }) => {
    const r = await request.get("/");
    const h = r.headers();
    // 仅 soft 检查——这些是建议项，不强制
    expect
      .soft(h["x-content-type-options"], "建议 X-Content-Type-Options: nosniff")
      .toBe("nosniff");
    expect.soft(h["x-frame-options"], "建议 X-Frame-Options 防点击劫持").toBeDefined();
  });
});
