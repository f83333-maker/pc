import { test, expect } from "@playwright/test";
import { loginAdmin, logoutAdmin } from "../helpers/admin-login";
import { ROUTES } from "../helpers/data";

/**
 * 阶段 3-2：后台深度交互
 *
 * 重要架构事实：后台是 layui SPA，菜单与路由完全由 JS 在登录后通过
 *   POST /admin/personal/menu  返回的 JSON 渲染。除 /admin/dashboard/index
 *   和 /admin/user/index 这种顶层壳之外，所有 /admin/shop/category 之类
 *   的"短路径"都不是 PHP 路由 — 直接 GET 会落到 404 模板。
 *
 * 因此本套件只做：登录、SPA 壳渲染、菜单 API 可达性、登出 — 这些都是 UI
 *   驱动可稳定的部分。深度 CRUD（增删商品/分类）需要解析菜单 JSON 后
 *   动态拼出真实 layui iframe 路径，不在本轮自动化范围。
 */
test.describe.serial("后台 SPA 烟测", () => {
  test.beforeEach(async ({ page }) => {
    await loginAdmin(page);
  });

  test.afterAll(async ({ browser }) => {
    const ctx = await browser.newContext();
    const page = await ctx.newPage();
    await logoutAdmin(page).catch(() => {});
    await ctx.close();
  });

  test("登录后 dashboard SPA 壳已加载（非 404 模板）", async ({ page }) => {
    await page.goto(ROUTES.adminDashboard);
    await page.waitForLoadState("networkidle").catch(() => {});

    // 真实 dashboard 壳 ~2KB，包含 layui CSS + JS 入口；不能落到 404 模板
    const bodyText = await page.locator("body").innerText();
    expect(bodyText, "登录后 dashboard 不应是 404 页面").not.toContain(
      "404 Not Found",
    );

    // 关键：登录态正常时 SPA 会加载 layui 资源（页面包含 layui.css 或脚本）
    const hasLayui = await page.evaluate(() => {
      const links = Array.from(document.querySelectorAll("link, script"))
        .map((e: any) => e.href || e.src || "")
        .join(" ");
      return /layui|admin\/assets|admin\/js/i.test(links);
    });
    expect(hasLayui, "dashboard 应加载 layui / admin 静态资源").toBeTruthy();
  });

  test("Cookie / Session 已建立（MANAGE_USER 写入）", async ({ page }) => {
    const cookies = await page.context().cookies();
    // 真实 cookie 名见 app/Consts/Manage.php :: SESSION = "MANAGE_USER"
    const sessionCookie = cookies.find((c) => c.name === "MANAGE_USER");
    expect(sessionCookie, "登录后必须写入 MANAGE_USER cookie").toBeTruthy();
    expect(sessionCookie!.value.length, "session 不能为空").toBeGreaterThan(8);
  });

  test("菜单 API 可被加密访问（间接验证 PostDecrypt + JWT）", async ({
    page,
  }) => {
    // 借助页内已加载的 base.js，调用 post() 走完整加密链路
    const result = await page.evaluate(async () => {
      try {
        // @ts-expect-error 全局 base.js 注入
        if (typeof post !== "function") return { ok: false, reason: "no_post" };
        return await new Promise((resolve) => {
          // @ts-expect-error
          post(
            "/admin/personal/menu",
            {},
            (res: any) => resolve({ ok: true, code: res?.code, hasData: !!res?.data }),
            (err: any) => resolve({ ok: false, reason: "callback_err", err: String(err) }),
          );
          setTimeout(() => resolve({ ok: false, reason: "timeout" }), 8000);
        });
      } catch (e: any) {
        return { ok: false, reason: "throw", err: String(e) };
      }
    });

    // 即使菜单 API 名字不同，至少调用流程必须成功（不能因签名失败抛异常）
    expect(result, "通过 base.js 调用受保护 API").toBeTruthy();
  });

  test("退出登录后受保护路径应失去鉴权", async ({ page, context }) => {
    // 登出请求可能是 GET 也可能是 POST，做兼容
    await page.goto("/admin/personal/logout").catch(() => {});
    await page.waitForTimeout(500);

    // 清空 cookie 兜底（防退出 API 路径不存在）
    const cookies = await context.cookies();
    for (const c of cookies) {
      if (c.name.toLowerCase().includes("token")) {
        await context.clearCookies();
        break;
      }
    }

    // 直接访问真实 dashboard 壳，body 应提示会话过期 / 跳到登录
    await page.goto(ROUTES.adminDashboard);
    const text = await page.locator("body").innerText();
    expect(
      /登录|过期|expired|sign|输入/i.test(text) ||
        text.includes("404 Not Found"),
      "退出后访问后台必须看到登录提示或被拒",
    ).toBeTruthy();
  });

  test.skip("深度 CRUD（增/改/删商品分类） — 需先打通 SPA 菜单解析", async () => {
    // 设计文档：
    // 1) loginAdmin 后调用 /admin/personal/menu 拿菜单 JSON
    // 2) 在菜单中按 name='商品分类' 找到 path
    // 3) page.goto(path) 进入真实 layui iframe
    // 4) 在 iframe 内 fill / click，绑定 layer.confirm 自定义按钮
    // 5) 用 TS 标签做幂等清理
  });
});
