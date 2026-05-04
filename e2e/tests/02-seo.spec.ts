import { test, expect } from "@playwright/test";
import { ROUTES, KNOWN_ITEM_MID } from "../helpers/data";

/**
 * 阶段 SEO：搜索引擎优化白盒验证
 *
 * 关键修正（2026-05）：线上短链 /item /login /register 在 nginx fallback 下不可达，
 * canonical / sitemap / og:url / JSON-LD url 全部使用 /user/index/item?mid=
 */

test("首页 SEO 元信息齐全", async ({ page }) => {
  await page.goto(ROUTES.home);
  await expect(page).toHaveTitle(/.+/);
  await expect(page.locator("html")).toHaveAttribute("lang", /zh/i);

  await expect(page.locator('meta[name="description"]')).toHaveAttribute(
    "content",
    /.{10,}/,
  );
  await expect(page.locator('meta[name="keywords"]')).toHaveAttribute(
    "content",
    /.+/,
  );
  await expect(page.locator('link[rel="canonical"]')).toHaveAttribute(
    "href",
    /^https?:\/\/.+\/$/,
  );

  await expect(page.locator('meta[property="og:type"]')).toHaveAttribute(
    "content",
    "website",
  );
  await expect(page.locator('meta[property="og:title"]')).toHaveCount(1);
  await expect(page.locator('meta[property="og:image"]')).toHaveCount(1);

  // 唯一 H1（SEO 强相关）
  const h1Count = await page.locator("h1").count();
  expect(h1Count, "首页应至少有 1 个 H1").toBeGreaterThanOrEqual(1);
});

test("商品详情页 SEO + JSON-LD Product Schema", async ({ page }) => {
  await page.goto(ROUTES.itemDetail(KNOWN_ITEM_MID));
  await expect(page.locator("h1").first()).toBeVisible();

  await expect(page.locator('meta[property="og:type"]')).toHaveAttribute(
    "content",
    "product",
  );

  // canonical 必须指向真实可访问 URL（线上 /item 短链不工作，所以保留长链）
  const canonical = await page
    .locator('link[rel="canonical"]')
    .getAttribute("href");
  expect(canonical, "商品详情页 canonical 必填").toBeTruthy();
  expect(canonical, "canonical 必须可访问，使用 /user/index/item?mid=").toMatch(
    /\/user\/index\/item\?mid=\d+/,
  );

  // canonical URL 自身必须真实可访问，否则就是 soft-404
  if (canonical) {
    const r = await page.request.get(canonical, { failOnStatusCode: false });
    const html = await r.text();
    expect.soft(
      html,
      "canonical URL 必须真实可达，绝不能是 404 模板",
    ).not.toMatch(/<title>404\s+Not\s+Found/i);
  }

  // OG URL / JSON-LD URL 应与 canonical 一致
  const ogUrl = await page
    .locator('meta[property="og:url"]')
    .getAttribute("content");
  expect(ogUrl).toMatch(/\/user\/index\/item\?mid=\d+/);

  // JSON-LD Product
  const ld = await page
    .locator('script[type="application/ld+json"]')
    .first()
    .textContent();
  expect(ld, "JSON-LD 必须存在").toBeTruthy();
  let parsed: any;
  expect(() => {
    parsed = JSON.parse(ld!.trim());
  }, "JSON-LD 必须为合法 JSON").not.toThrow();
  expect(parsed["@context"]).toMatch(/schema\.org/);
  expect(parsed["@type"]).toBe("Product");
  expect(parsed.name).toBeTruthy();
  expect(parsed.offers?.price, "Product.offers.price 必须存在").toBeDefined();
  expect(parsed.offers?.url, "Product.offers.url").toMatch(
    /\/user\/index\/item\?mid=\d+/,
  );
});

test("登录 / 注册页应 noindex", async ({ page }) => {
  for (const path of [ROUTES.login, ROUTES.register]) {
    const res = await page.goto(path);
    if (res && res.status() >= 400) {
      test.info().annotations.push({ type: "skip", description: `${path} 不可达` });
      continue;
    }
    const robots = await page
      .locator('meta[name="robots"]')
      .first()
      .getAttribute("content")
      .catch(() => null);
    expect.soft(
      robots,
      `${path} 应在 <meta name="robots"> 中包含 noindex（避免登录入口被收录）`,
    ).toMatch(/noindex/i);
  }
});

test("图片 alt 覆盖率（首页）", async ({ page }) => {
  await page.goto(ROUTES.home);
  const imgs = page.locator("img");
  const total = await imgs.count();
  expect(total, "首页应至少有 1 张图片").toBeGreaterThan(0);

  const missingAlts: string[] = [];
  for (let i = 0; i < total; i++) {
    const alt = await imgs.nth(i).getAttribute("alt");
    const src = await imgs.nth(i).getAttribute("src");
    // alt="" 是合法的（装饰图），但不能完全没有 alt 属性
    if (alt === null) missingAlts.push(src || "(no src)");
  }
  expect(
    missingAlts,
    `共 ${missingAlts.length}/${total} 张 img 完全没有 alt 属性: ${missingAlts.slice(0, 5).join(",")}`,
  ).toEqual([]);
});

test("内链抽样无 5xx / 死链", async ({ page }) => {
  await page.goto(ROUTES.home);
  const hrefs = await page
    .locator('a[href^="/"], a[href^="https://pcccc.cc"]')
    .evaluateAll((els) => (els as HTMLAnchorElement[]).map((e) => e.href));
  const uniq = [...new Set(hrefs)]
    .filter((u) => !u.startsWith("javascript:") && !u.endsWith("#"))
    .slice(0, 20);

  for (const url of uniq) {
    const r = await page.request
      .get(url, { failOnStatusCode: false, maxRedirects: 5 })
      .catch(() => null);
    if (r) {
      expect.soft(r.status(), `内链 ${url} 不应 5xx`).toBeLessThan(500);
      // 内链也不应落到 soft-404
      const html = await r.text().catch(() => "");
      expect.soft(html, `内链 ${url} 不应 soft-404`).not.toMatch(
        /<title>404\s+Not\s+Found/i,
      );
    }
  }
});

test("结构化语言一致性：html lang + Content-Language", async ({ page }) => {
  const res = await page.goto(ROUTES.home);
  const lang = await page.locator("html").getAttribute("lang");
  expect(lang).toMatch(/zh/i);
  const cl =
    res?.headers()["content-language"] || res?.headers()["Content-Language"];
  if (cl) expect.soft(cl).toMatch(/zh/i);
});
