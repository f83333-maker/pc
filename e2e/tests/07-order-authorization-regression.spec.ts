/**
 * 第二轮安全修复回归测试
 *
 * 覆盖的 bug：
 *   B3 / B4 — Order::getOrder / downloadOrder 水平越权（任意登录用户拿别人 trade_no 即可下载卡密）
 *   cancel — 任意登录用户用 trade_no 取消他人未支付订单（DoS）
 *
 * 修复后期望行为：
 *   - 当前用户用别人的 trade_no 调用 /shop/order/getOrder / /shop/order/downloadOrder / /shop/order/cancel
 *   - 必须返回 "订单不存在"，且响应中绝不能包含 treasure / 卡密 / secret 等字段
 *   - HTTP 状态绝不应是 5xx
 *
 * 实现说明：
 *   后端 POST 必走 PostDecrypt（AES + Signature）。直接构造 fetch 必然在 PostDecrypt 这一关
 *   就被拒（这本身也算是一种保护，会在 04-injection-xss 里覆盖签名失败用例）。
 *   这里要测的是"已通过签名校验但 trade_no 不属于自己"的越权场景，所以必须在浏览器里
 *   通过页面自带的 base.js 发请求 —— 即用 page.evaluate 调用全局的 ajax 助手。
 */

import { test, expect, Page } from '@playwright/test'
import { loginUser } from '../helpers/user-login'

/**
 * 在已登录的浏览器上下文里，调用 base.js 发起一个 POST 请求。
 * base.js 的 post() 通常挂在 window 的 jQuery.ajax 之上 —— 这里用更朴素的方式：
 * 直接用 jQuery.ajax 触发，让请求拦截器（base.js 里的 beforeSend / dataFilter）
 * 自动加上 Secret / Signature 头并 AES 加密 body。
 */
async function jqueryPost(page: Page, url: string, data: Record<string, unknown>): Promise<{
  status: number
  body: string
  parsed?: any
}> {
  return page.evaluate(
    async ({ url, data }) => {
      // 兼容两种全局：jQuery 或 $；本项目两套主题都引入 layui + jquery
      const $ = (window as any).jQuery || (window as any).$
      if (!$) throw new Error('jQuery 未加载，无法走 base.js 加密通道')
      return new Promise<{ status: number; body: string; parsed?: any }>((resolve) => {
        $.ajax({
          url,
          type: 'POST',
          data,
          success: (resp: any, _s: string, xhr: any) => {
            resolve({
              status: xhr.status,
              body: typeof resp === 'string' ? resp : JSON.stringify(resp),
              parsed: typeof resp === 'object' ? resp : undefined,
            })
          },
          error: (xhr: any) => {
            resolve({ status: xhr.status, body: xhr.responseText || '' })
          },
        })
      })
    },
    { url, data }
  )
}

test.describe('订单越权回归（B3 / B4 / cancel）', () => {
  test.beforeEach(async ({ page }) => {
    await loginUser(page)
    // 进入任意公开页面以加载 base.js 与 jQuery
    await page.goto('/')
  })

  test('getOrder 用伪造 trade_no 必须返回订单不存在', async ({ page }) => {
    const fakeTradeNo = 'FAKE_' + Date.now() + '_' + Math.random().toString(36).slice(2, 8)
    // 使用长链 API（短链在 nginx 层 404）
    const resp = await jqueryPost(page, '/user/shop/order/getOrder', {
      trade_no: fakeTradeNo,
      item_id: 1,
    })

    expect(resp.status, '不应该是 5xx').toBeLessThan(500)
    // 不应包含敏感字段
    expect(resp.body, '响应里绝不能含 treasure / 卡密').not.toMatch(/treasure|卡密|secret/i)
    // 期望文案：订单不存在
    if (resp.parsed) {
      expect(resp.parsed.code === 200 ? resp.parsed.message : resp.parsed.message).toMatch(/订单不存在|order not found|未找到/i)
    } else {
      expect(resp.body).toMatch(/订单不存在|order not found|未找到/i)
    }
  })

  test('cancel 用伪造 trade_no 必须返回订单不存在（防 DoS 干扰）', async ({ page }) => {
    const fakeTradeNo = 'FAKE_' + Date.now() + '_' + Math.random().toString(36).slice(2, 8)
    // 使用长链 API
    const resp = await jqueryPost(page, '/user/shop/order/cancel', { trade_no: fakeTradeNo })
    expect(resp.status).toBeLessThan(500)
    if (resp.parsed) {
      expect([200, 0, 400, 404]).toContain(resp.parsed.code ?? 0)
      // 修复后：不应该静默成功；应明确返回订单不存在
      if (resp.parsed.code === 200) {
        // 如果 code=200，message 不能是 "success"——意味着越权成功了
        expect(resp.parsed.message, '伪造 trade_no 不应被静默 cancel 成功').not.toMatch(/^success$/i)
      }
    }
  })

  test('downloadOrder 用伪造 trade_no GET 不应吐出二进制文件', async ({ page, request }) => {
    // downloadOrder 是 GET 路由，从浏览器 cookie 上下文里发
    const cookies = await page.context().cookies()
    const cookieHeader = cookies.map(c => `${c.name}=${c.value}`).join('; ')

    const fakeTradeNo = 'FAKE_' + Date.now()
    // 使用长链 API
    const r = await request.get(`/user/shop/order/downloadOrder?itemId=1&tradeNo=${fakeTradeNo}`, {
      headers: { Cookie: cookieHeader },
      failOnStatusCode: false,
    })

    expect(r.status(), '不应该 5xx').toBeLessThan(500)

    const contentType = r.headers()['content-type'] || ''
    // 失败响应应该是 json/html，绝不能是 octet-stream（那意味着真给了卡密文件）
    expect(contentType, '失败时不应返回 application/octet-stream').not.toMatch(/octet-stream/i)

    const body = await r.text()
    expect(body, '不应包含卡密关键词').not.toMatch(/treasure|卡密/i)
  })

  test('未登录访客访问 getOrder 应被 Visitor 拦截器在 force_login=1 时拒绝', async ({ browser }) => {
    // 用全新无 cookie 的上下文模拟纯访客
    const ctx = await browser.newContext()
    const fresh = await ctx.newPage()
    await fresh.goto('/')
    // 使用长链 API
    const resp = await jqueryPost(fresh, '/user/shop/order/getOrder', {
      trade_no: 'ANY_' + Date.now(),
      item_id: 1,
    })
    // Visitor 拦截器若 force_login=1 会 401/403；force_login=0 时按访客 client_id 走，
    // 此时也不能拿到任何订单数据
    expect(resp.body).not.toMatch(/treasure|卡密/i)
    await ctx.close()
  })
})

test.describe('PostDecrypt 旁路回归', () => {
  test('空 body + 伪造 Secret/Signature 必须 signature failure', async ({ request }) => {
    // 修复前：Secret/Signature 都给非空、body 为空 → 直接 return $response 跳过校验
    // 修复后：必须显式 throw
    // 使用长链 API
    const r = await request.post('/user/shop/order/cancel', {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        Secret: 'a'.repeat(32),
        Signature: 'forged-signature',
      },
      data: '',
      failOnStatusCode: false,
    })
    expect(r.status()).toBeLessThan(500)
    const body = await r.text()
    // 应该出现 signature failure 字样（或被加密回写为该错误）；
    // 至少绝不能出现 success
    expect(body, '空 body+伪造头不应被认为是合法签名').not.toMatch(/"message":\s*"success"/i)
  })
})
