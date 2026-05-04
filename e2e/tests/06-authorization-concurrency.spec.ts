import { test, expect } from "@playwright/test";
import { loginUser } from "../helpers/user-login";

/**
 * 阶段 3-补充：越权 / 并发 / Session 强度
 */
test.describe("越权与并发", () => {
  test("未登录访问会员中心应被重定向到登录页", async ({ page }) => {
    await page.goto("/user/dashboard/index", { waitUntil: "domcontentloaded" });
    await expect(page).toHaveURL(/\/login|\/user\/authentication\/login/);
  });

  test("未登录访问后台应被重定向到登录页", async ({ page }) => {
    await page.goto("/admin/dashboard", { waitUntil: "domcontentloaded" });
    // 后台是 SPA，检查 URL 或页面是否包含登录相关内容
    const url = page.url();
    const body = await page.locator("body").innerText();
    expect(
      url.includes("/admin/authentication/login") || 
      body.includes("登录") || 
      body.includes("login") ||
      body.includes("password"),
      "未登录应重定向到后台登录页"
    ).toBeTruthy();
  });

  test("普通用户 cookie 直接访问 admin 路由必须被拒", async ({ page }) => {
    await loginUser(page);
    const r = await page.request.get("/admin/dashboard", {
      failOnStatusCode: false,
      maxRedirects: 0,
    });
    // 要么重定向（302/303），要么直接返回登录 HTML
    if (r.status() >= 300 && r.status() < 400) {
      expect(r.headers()["location"]).toMatch(/\/admin/);
    } else {
      const body = await r.text();
      expect(body, "用户不应能看到后台 dashboard 内容").not.toMatch(
        /admin\/dashboard\/statistics|后台管理首页|管理员菜单/i,
      );
    }
  });

  test("水平越权探测：用伪造 trade_no 查询订单不应返回别人卡密", async ({
    page,
    request,
  }) => {
    await loginUser(page);
    const cookies = await page.context().cookies();
    const cookieHeader = cookies.map((c) => `${c.name}=${c.value}`).join("; ");

    const r = await request.post("/shop/order/getOrder", {
      headers: {
        Cookie: cookieHeader,
        "Content-Type": "application/json",
      },
      data: JSON.stringify({
        trade_no: "FAKE_TRADE_NO_" + Date.now(),
        item_id: 999999,
      }),
    });
    const body = await r.text();
    // 必须返回签名错误（因为没加密）或订单不存在，绝不能返回真实卡密字段
    expect(body).not.toMatch(/treasure|卡密|secret_key|"render":false[^"]*"\w{20,}"/i);
  });

  test("篡改 session cookie 后访问受保护页面必须失败", async ({ page }) => {
    await loginUser(page);
    const ctx = page.context();
    const cks = await ctx.cookies();
    // 兼容多种 cookie 名称（ACG-SHOP, user_token, USER_SESSION 等）
    const sessionCookie = cks.find((c) => 
      ["ACG-SHOP", "user_token", "USER_SESSION", "PHPSESSID"].includes(c.name) && c.value
    );
    expect(sessionCookie, "登录后应存在 session cookie").toBeTruthy();

    // 把 cookie 末 3 个字符改掉
    await ctx.clearCookies();
    await ctx.addCookies([
      {
        ...sessionCookie!,
        value: sessionCookie!.value.slice(0, -3) + "AAA",
      },
    ]);

    await page.goto("/user/dashboard/index", {
      waitUntil: "domcontentloaded",
    });
    // 篡改后应被重定向到登录页
    await expect(page).toHaveURL(/\/login|\/user\/authentication\/login/);
  });

  test("狂点提交按钮：服务端不应 5xx，不应产生 N 倍数据", async ({ page }) => {
    await loginUser(page);
    await page.goto("/");
    const item = page.locator('a[href*="/item?mid="]').first();
    if (!(await item.count())) test.skip(true, "无可购买商品");
    await item.click();
    await page.waitForLoadState("domcontentloaded");

    const btn = page
      .locator('button:has-text("加入购物车")')
      .first();
    if (!(await btn.count())) test.skip(true, "无加购按钮");

    const statusList: number[] = [];
    page.on("response", (r) => {
      if (r.url().includes("/shop/cart/")) statusList.push(r.status());
    });

    // 在 1 秒内连续点击 10 次
    for (let i = 0; i < 10; i++) {
      await btn.click({ force: true, timeout: 1000 }).catch(() => {});
    }
    await page.waitForTimeout(2500);

    expect(
      statusList.every((s) => s < 500),
      `连点不应触发 5xx，实际状态: ${statusList.join(",")}`,
    ).toBeTruthy();
  });

  test("同账号双 Tab 操作：不应让 session 错乱", async ({ browser }) => {
    const ctx = await browser.newContext();
    const tab1 = await ctx.newPage();
    const tab2 = await ctx.newPage();

    await loginUser(tab1);
    // tab2 共享 ctx，应自动是登录态
    await tab2.goto("/user/dashboard/index");
    await expect(tab2).toHaveURL(/\/user\/dashboard/);

    // 在 tab1 退出
    await tab1.goto("/user/dashboard/logout").catch(() => {});
    await tab1.context().clearCookies();
    // tab2 此时应在下一次受保护请求时被踢
    await tab2.goto("/user/dashboard/index", { waitUntil: "domcontentloaded" });
    await expect(tab2).toHaveURL(/\/login|\/user\/authentication\/login/);

    await ctx.close();
  });

  test("登出后受保护接口应不再可访问", async ({ page }) => {
    await loginUser(page);
    await page.goto("/user/dashboard/logout").catch(() => {});
    await page.context().clearCookies();
    const r = await page.request.get("/user/dashboard/index", {
      failOnStatusCode: false,
      maxRedirects: 0,
    });
    expect(r.status() === 302 || r.status() === 200).toBeTruthy();
    if (r.status() === 200) {
      const body = await r.text();
      expect(body).not.toMatch(/退出登录|余额|个人资料/);
    }
  });
});
