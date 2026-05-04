import { test, expect } from "@playwright/test";
import { ROUTES, KNOWN_ITEM_MID } from "../helpers/data";

/**
 * 阶段 1：基础连通性
 * - 遍历前端核心公开页，HTTP 状态码 < 400
 * - 关键 DOM 渲染存在
 * - robots.txt / sitemap.xml / 404 / 安装锁等"门面"健康检查
 *
 * 重要：URL 全部使用 helpers/data.ts 的 ROUTES 常量（线上真实路由）。
 * 短链 /login /register /item /shop/cart 在 nginx fallback 下都会落 404 模板。
 */

const PUBLIC_PAGES = [
  { name: "首页", path: ROUTES.home, must: ["html", "title", "nav"] },
  {
    name: "商品详情",
    path: ROUTES.itemDetail(KNOWN_ITEM_MID),
    must: ["html", "title"],
  },
  {
    name: "登录页",
    path: ROUTES.login,
    must: ["form", 'input[name="username"]', 'input[name="password"]'],
  },
  {
    name: "注册页",
    path: ROUTES.register,
    must: ["form", 'input[name="username"]'],
  },
  { name: "订单查询", path: ROUTES.query, must: ["html"] },
  {
    name: "后台登录",
    path: ROUTES.admin,
    must: ["form", 'input[type="password"]'],
  },
];

for (const p of PUBLIC_PAGES) {
  test(`公开页可达且 DOM 正常: ${p.name} (${p.path})`, async ({ page }) => {
    const res = await page.goto(p.path);
    expect(res, `${p.path} 必须有响应`).not.toBeNull();
    const status = res!.status();
    expect.soft(status, `${p.path} HTTP 状态`).toBeGreaterThanOrEqual(200);
    expect.soft(status, `${p.path} HTTP 状态`).toBeLessThan(400);

    // 重要：title 不能是 404 模板（防 soft-404）
    const title = await page.title();
    expect(
      title,
      `${p.path} 不能是 404 模板（soft-404 会被 Google 严重打压）`,
    ).not.toMatch(/^404\s+Not\s+Found/i);

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
  expect(body).toMatch(/Allow:\s*\/user\/index\/item/);
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

test("sitemap 中所有 loc 真实可访问（不是 soft-404）", async ({ request }) => {
  const r = await request.get("/sitemap.xml");
  const body = await r.text();
  const locs = [...body.matchAll(/<loc>([^<]+)<\/loc>/g)]
    .map((m) => m[1])
    .slice(0, 8); // 最多抽 8 条避免太慢
  expect(locs.length, "sitemap 至少要有 1 个 URL").toBeGreaterThan(0);

  for (const url of locs) {
    const res = await request.get(url, { failOnStatusCode: false });
    const html = await res.text();
    expect.soft(res.status(), `${url} 状态码`).toBeLessThan(500);
    // 关键：sitemap 里的 URL 不能落到 404 模板
    expect.soft(
      html,
      `sitemap 中的 ${url} 必须真实渲染，不能落到 404 模板（soft-404 = SEO 灾难）`,
    ).not.toMatch(/<title>404\s+Not\s+Found/i);
  }
});

test("未知路由应返回真正的 404 状态码（防 soft-404）", async ({ page }) => {
  const res = await page.goto("/this-route-should-never-exist-" + Date.now(), {
    waitUntil: "domcontentloaded",
  });
  // 这一项是当前线上的真实问题：404 页面 HTTP 状态返回 200，对 SEO 是隐患
  // 期望行为：HTTP 状态应为 404
  expect.soft(
    res?.status(),
    "未知路由应该返回 HTTP 404，而不是 200（soft-404 会被 Google 降权）",
  ).toBe(404);
});

test("/install 应已锁定", async ({ request }) => {
  const r = await request.get("/install");
  const body = await r.text();
  // 锁定后框架渲染 Rewrite.html 或 404，绝不能出现安装向导关键词
  expect(body, "/install 在生产环境绝不能暴露").not.toMatch(
    /数据库连接|database\s*password|安装第一步|MySQL\s*Hostname/i,
  );
});

test("favicon 存在", async ({ request }) => {
  // 项目实际用 favicon.png，不是 .ico
  const r = await request.get("/favicon.png");
  expect(r.status()).toBe(200);
});
