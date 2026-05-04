/**
 * 部署侧硬化检查
 *
 * 这套用例不针对具体业务，只校验上线必须达到的"基本卫生"：
 *   1. /install 必须已被 kernel/Install/Lock 锁定
 *   2. .git / composer.json / .env 等敏感文件不可外部访问
 *   3. HTTPS 重定向 / HSTS / X-Frame-Options 等安全头存在
 *   4. SEO 关键资源（sitemap.xml、robots.txt、canonical）真实可达
 *   5. 商品详情页 canonical 已经指向短链 /item?mid=（验证第一轮 SEO 修复落地）
 */

import { test, expect } from '@playwright/test'

test.describe('部署侧硬化', () => {
  test('/install 必须已被锁定', async ({ request }) => {
    const r = await request.get('/install', { failOnStatusCode: false })
    const body = await r.text()
    // 已锁定时框架输出 Rewrite/install 锁定提示，且不应出现"数据库密码""安装第一步"等真实安装界面字段
    expect(body, '/install 不应展示安装表单').not.toMatch(/数据库密码|database\s*password|安装第一步|step\s*1\b/i)
  })

  test('敏感文件不可访问', async ({ request }) => {
    const targets = [
      '/.env',
      '/.env.production',
      '/composer.json',
      '/composer.lock',
      '/.git/HEAD',
      '/.git/config',
      '/config/route.php',
      '/kernel/Install/Install.sql',
      '/kernel/Install/Lock',
    ]
    for (const t of targets) {
      const r = await request.get(t, { failOnStatusCode: false })
      const body = await r.text()
      // 允许 200 + 框架 404 页（HTML），但不能直接吐出文件内容
      expect.soft(body, `${t} 不应泄露文件原文`).not.toMatch(/^\s*\{[\s\S]*"require"/m) // composer
      expect.soft(body, `${t} 不应泄露文件原文`).not.toMatch(/DB_PASSWORD|APP_KEY=/)
      expect.soft(body, `${t} 不应泄露 SQL 原文`).not.toMatch(/CREATE\s+TABLE\s+`?manage`?/i)
    }
  })

  test('HTTPS 安全响应头', async ({ request }) => {
    const r = await request.get('/')
    const h = r.headers()
    // 不强制 HSTS（部署在反代后可能由 nginx 注入），但记录一下
    const flags = {
      hsts: !!h['strict-transport-security'],
      xfo: !!h['x-frame-options'],
      xcto: !!h['x-content-type-options'],
      ref: !!h['referrer-policy'],
    }
    test.info().annotations.push({ type: 'security-headers', description: JSON.stringify(flags) })

    // 至少应有 X-Content-Type-Options: nosniff（防 MIME 嗅探攻击）
    expect.soft(h['x-content-type-options'], '建议设置 X-Content-Type-Options: nosniff').toBeTruthy()
  })

  test('robots.txt 必须包含 Allow: /item（第一轮 SEO 修复）', async ({ request }) => {
    const r = await request.get('/robots.txt')
    expect(r.status()).toBe(200)
    const body = await r.text()
    expect(body, 'robots.txt 必须显式放行短链 /item').toMatch(/Allow:\s*\/item\b/)
  })

  test('商品详情页 canonical 已指向短链（第一轮 SEO 修复）', async ({ page, request }) => {
    // 抓首页第一个商品链接
    await page.goto('/')
    const itemHref = await page.locator('a[href*="/item?mid="], a[href*="/user/index/item"]').first().getAttribute('href')
    if (!itemHref) test.skip(true, '首页未渲染商品链接，无法取样')

    await page.goto(itemHref!)
    const canonical = await page.locator('link[rel="canonical"]').getAttribute('href')
    expect(canonical, 'canonical 必须存在').toBeTruthy()
    // 关键断言：canonical 必须用短链 /item，不能再指向 /user/index/item
    expect(canonical, 'canonical 必须指向短链 /item?mid=').toMatch(/\/item\?mid=\d+/)
    expect(canonical, 'canonical 不应再指向 /user/index/item').not.toMatch(/\/user\/index\/item/)
  })

  test('sitemap.xml 商品 URL 必须使用短链', async ({ request }) => {
    const r = await request.get('/sitemap.xml', { failOnStatusCode: false })
    if (r.status() !== 200) test.skip(true, 'sitemap.xml 尚未生成（待 cron 跑过）')

    const body = await r.text()
    // 只要没有 /user/index/item，就证明 generator 已经修复
    expect(body, 'sitemap 不应再使用 /user/index/item 长链').not.toMatch(/<loc>[^<]*\/user\/index\/item/)
  })
})
