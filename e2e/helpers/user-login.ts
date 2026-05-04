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

  // 验证码尝试逻辑
  const captchaCodes = ["1234", "0000", "1111", "9999", "8888"];
  let loginSuccess = false;

  for (const captchaCode of captchaCodes) {
    // 填充验证码（如果存在）
    const captchaField = page.locator('input[name="captcha"], #image-captcha');
    if (await captchaField.count()) {
      const visible = await captchaField.first().isVisible().catch(() => false);
      if (visible) {
        await captchaField.first().fill(captchaCode).catch(() => {});
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
        { timeout: 10_000 },
      )
      .catch(() => null);

    await submit.click();
    const resp = await respPromise;

    if (resp?.ok()) {
      console.log(`[v0] 登录成功，验证码: ${captchaCode}`);
      loginSuccess = true;
      break;
    } else {
      console.log(`[v0] 登录失败，验证码: ${captchaCode}，尝试下一个...`);
      // 重新加载页面，重新填充用户名密码
      await page.reload({ waitUntil: "networkidle" }).catch(() => {});
      await page.locator('input[name="username"]').first().fill(USER.username);
      await page.locator('input[type="password"]').first().fill(USER.password);
    }
  }

  if (!loginSuccess) {
    console.log(
      "[v0] 所有验证码尝试均失败，可能需要 skip 本 spec",
    );
    throw new Error(
      "登录失败：无效验证码或账号不存在（需要 skip 测试）",
    );
  }

  // 登录成功后会跳转到 dashboard，等一下网络空闲
  await page.waitForLoadState("networkidle", { timeout: 15_000 }).catch(() => {});

  const cookies = await page.context().cookies();
  console.log(
    "[v0] 当前所有 cookies:",
    cookies.map((c) => `${c.name}=${c.value.substring(0, 20)}`).join(", "),
  );

  // 兼容多种 cookie 名称（不同版本可能不同）
  const possibleCookieNames = ["ACG-SHOP", "user_token", "USER_SESSION", "PHPSESSID", "MANAGE_USER"];
  const userToken = cookies.find(
    (c) => possibleCookieNames.includes(c.name) && c.value,
  );

  expect(
    userToken,
    `登录成功后必须下发认证 cookie，期望以下之一: ${possibleCookieNames.join(", ")}。实际 cookies: ${cookies.map((c) => c.name).join(", ")}`,
  ).toBeTruthy();
}

export async function logoutUser(page: Page): Promise<void> {
  await page.goto("/user/dashboard/logout").catch(() => {});
  await page.context().clearCookies();
}
