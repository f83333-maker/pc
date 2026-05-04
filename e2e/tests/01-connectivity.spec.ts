import { test, expect } from "@playwright/test";

/**
 * 阶段 1：基础连通性
 * - 遍历前端核心公开页，HTTP 状态码 < 400
 * - 关键 DOM 渲染存在
 * - robots.txt / sitemap.xml / 404 / 安装锁等"门面"健康检查
 */

const PUBLIC_PAGES = [
  { path: "/",            must: ["html", "title"] },
  { path: "/item?mid=1",  must: ["html", "title"] },
  { path: "/login",       must: ["form", 'input[type="password"]'] },
  { path: "/register",    must: ["form", "input"] },
  { path: "/reset",       must: ["form"] },
  { path: "/checkout",    must: ["html"] },
  { path: "/shop/cart",   must: ["html"] },
  { path: "/admin",       must: ["form", 'input[type="password"]'] },
];

for (const p of PUBLIC_PAGES) {
  test(`公开页可达且 DOM 正常: ${p.path}`, async ({ page }) => {
    const res = await page.goto(p.path);
    expect(res, `${p.path} 必须有响应`).not.toBeNull();
    const status = res!.status();
    expect.soft(status, `${p.path} HTTP 状态`).toBeGreaterThanOrEqual(200);
    expect.soft(status, `${p.path} HTTP 状态`).toBeLessThan(400);

    for (const sel of p.must) {
      await expect(
        page.locator(sel).first(),
        `${p.path} 必须包含选择器 ${sel}`,
      ).toBeAttached({ timeout: 5_000 });
    }
  });
}

test("robots.txt 内容合规", async ({ request }) => {
  const r = await request.get("/robots.txt");
  expect(r.status()).toBe(200);
  const body = await r.text();
  expect(body).toMatch(/User-agent:\s*\*/);
  expect(body).toMatch(/Disallow:\s*\/admin/);
  expect(body).toMatch(/Sitemap:\s+https?:\/\//i);
  // 应放行公开商品详情入口
  expect(body).toMatch(/Allow:\s*\/item/);
});

test("sitemap.xml 存在且为合法 XML", async ({ request }) => {
  const r = await request.get("/sitemap.xml");
  expect(r.status(), "sitemap.xml 必须可访问（建议 cron 定时生成）").toBe(200);
  const body = await r.text();
  expect(body).toMatch(/<urlset/);
  expect(body).toMatch(/<loc>https?:\/\//);
  // 必须包含至少首页
  expect(body).toMatch(/<loc>https?:\/\/[^<]+\/<\/loc>/);
});

test("404 页面应返回 404", async ({ page }) => {
  const res = await page.goto("/this-route-should-never-exist-" + Date.now(), {
    waitUntil: "domcontentloaded",
  });
  // 大多数 PHP 框架的 404 状态码是 404
  expect.soft(res?.status(), "未知路由应该是 404").toBe(404);
});

test("/install 应已锁定", async ({ request }) => {
  const r = await request.get("/install");
  const body = await r.text();
  // 锁定后框架渲染 Rewrite.html，绝不能出现安装向导关键词
  expect(body, "/install 在生产环境绝不能暴露").not.toMatch(
    /数据库连接|database\s*password|安装第一步|MySQL\s*Hostname/i,
  );
});

test("/hello 健康检查可达", async ({ request }) => {
  const r = await request.get("/hello");
  expect.soft(r.status()).toBeLessThan(500);
});

test("favicon.png 存在", async ({ request }) => {
  const r = await request.get("/favicon.png");
  expect(r.status()).toBe(200);
});
