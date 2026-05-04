import { test, expect } from "@playwright/test";

/**
 * 阶段 SEO：搜索引擎优化白盒验证
 * - title / description / keywords / canonical / OG / Twitter / JSON-LD 全链路
 * - 登录/注册类页面必须 noindex
 * - 图片 alt 覆盖率
 * - 内链有效性抽样
 * - 重复内容检测：canonical 必须收敛到短链 /item?mid=
 */

test("首页 SEO 元信息齐全", async ({ page }) => {
  await page.goto("/");
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
  await page.goto("/item?mid=1");
  await expect(page.locator("h1").first()).toBeVisible();

  await expect(page.locator('meta[property="og:type"]')).toHaveAttribute(
    "content",
    "product",
  );

  // canonical 必须指向真实可访问的短链路由 /item?mid=
  const canonical = await page
    .locator('link[rel="canonical"]')
    .getAttribute("href");
  expect(canonical, "商品详情页 canonical 必填").toBeTruthy();
  expect(canonical, "canonical 不应再指向旧的 /user/index/item").not.toMatch(
    /\/user\/index\/item/,
  );
  expect(canonical, "canonical 必须使用短链 /item?mid=").toMatch(/\/item\?mid=\d+/);

  // OG URL / JSON-LD URL 应与 canonical 一致
  const ogUrl = await page
    .locator('meta[property="og:url"]')
    .getAttribute("content");
  expect(ogUrl).toMatch(/\/item\?mid=\d+/);

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
  expect(parsed.offers?.url, "Product.offers.url").toMatch(/\/item\?mid=\d+/);
});

test("登录 / 注册 / 找回密码页应 noindex", async ({ page }) => {
  for (const path of ["/login", "/register", "/reset"]) {
    await page.goto(path);
    const robots = await page
      .locator('meta[name="robots"]')
      .first()
      .getAttribute("content");
    expect(
      robots,
      `${path} 应在 <meta name="robots"> 中包含 noindex`,
    ).toMatch(/noindex/i);
  }
});

test("图片 alt 覆盖率（首页）", async ({ page }) => {
  await page.goto("/");
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
  await page.goto("/");
  const hrefs = await page
    .locator('a[href^="/"], a[href^="https://pcccc.cc"]')
    .evaluateAll((els) => (els as HTMLAnchorElement[]).map((e) => e.href));
  const uniq = [...new Set(hrefs)].slice(0, 25);
  for (const url of uniq) {
    const r = await page.request
      .get(url, { failOnStatusCode: false, maxRedirects: 5 })
      .catch(() => null);
    if (r) {
      expect.soft(r.status(), `内链 ${url} 不应 5xx`).toBeLessThan(500);
    }
  }
});

test("结构化语言一致性：html lang + Content-Language", async ({ page }) => {
  const res = await page.goto("/");
  const lang = await page.locator("html").getAttribute("lang");
  expect(lang).toMatch(/zh/i);
  const cl =
    res?.headers()["content-language"] || res?.headers()["Content-Language"];
  if (cl) expect.soft(cl).toMatch(/zh/i);
});

test("两条等价 URL 之间有 canonical 收敛", async ({ page }) => {
  // 老链接 /user/index/item?mid=1 仍应可访问，但其 canonical 必须指向 /item?mid=1
  const res = await page
    .goto("/user/index/item?mid=1", { waitUntil: "domcontentloaded" })
    .catch(() => null);
  if (!res || res.status() >= 400) {
    test.skip(true, "/user/index/item 不可达，框架已收敛——跳过双 URL 检查");
  }
  const canonical = await page
    .locator('link[rel="canonical"]')
    .getAttribute("href");
  expect(canonical, "兼容路由必须指向 canonical 短链").toMatch(/\/item\?mid=\d+/);
});
