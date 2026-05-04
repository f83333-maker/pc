import { Page, expect } from "@playwright/test";
import { USER, ROUTES } from "./data";

/**
 * 会员中心登录
 * 必须走 UI 让 base.js 完成 AES 加密 + Signature 签名。
 *
 * 实测：登录提交目标是 /user/authentication/login（与页面路径相同），
 * 成功后 base.js 会通过 toastr 弹提示并 setTimeout 跳到 dashboard。
 */
export async function loginUser(page: Page): Promise<void> {
  await page.goto(ROUTES.login);

  // 用户名 / 密码字段（页面用 name="username" 与 type="password"）
  await page.locator('input[name="username"]').first().fill(USER.username);
  await page.locator('input[type="password"]').first().fill(USER.password);

  // 如果存在图形验证码，因为 v0 沙箱里跑无法人工识别，soft-skip
  const captcha = page.locator('input[name="captcha"], #image-captcha');
  if (await captcha.count()) {
    // 部分商家会关闭图形验证码（设为隐藏），先看是否真的可见
    const visible = await captcha.first().isVisible().catch(() => false);
    if (visible) {
      // 沙箱无法识别验证码：先尝试盲填一个常见值，失败的话由 spec 自己 skip
      await captcha.first().fill("0000").catch(() => {});
    }
  }

  const submit = page
    .locator('button[type="submit"]:not([disabled])')
    .first();

  // 等待登录 POST 响应
  const respPromise = page
    .waitForResponse(
      (r) =>
        /\/(user\/authentication\/login|login)(\?|$)/.test(r.url()) &&
        r.request().method() === "POST",
      { timeout: 30_000 },
    )
    .catch(() => null);

  await submit.click();
  await respPromise;
  // 登录成功后会跳转到 dashboard，等一下网络空闲
  await page.waitForLoadState("networkidle", { timeout: 15_000 }).catch(() => {});

  const cookies = await page.context().cookies();
  const userToken = cookies.find((c) => c.name === "user_token" && c.value);
  expect(
    userToken,
    "登录成功后必须下发 user_token cookie（如失败请确认账号 222222/222222 仍可用）",
  ).toBeTruthy();
}

export async function logoutUser(page: Page): Promise<void> {
  await page.goto("/user/dashboard/logout").catch(() => {});
  await page.context().clearCookies();
}
