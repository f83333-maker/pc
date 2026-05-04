import { Page, expect } from "@playwright/test";
import { USER } from "./data";

/**
 * 会员中心登录
 * 与后台同理，必须走 UI 让 base.js 完成 AES 加密。
 */
export async function loginUser(page: Page): Promise<void> {
  await page.goto("/login");

  await page
    .locator('input[name="username"], input[name="email"], input[type="text"]')
    .first()
    .fill(USER.username);
  await page.locator('input[type="password"]').first().fill(USER.password);

  const submit = page
    .locator('button[type="submit"], button:has-text("登录"), button:has-text("登 录")')
    .first();

  // 等待 POST /login 响应——成功后会写 user_token cookie
  await Promise.all([
    page.waitForResponse(
      (r) => /\/login(\?|$)/.test(r.url()) && r.request().method() === "POST",
      { timeout: 30_000 },
    ).catch(() => null),
    submit.click(),
  ]);

  await page.waitForLoadState("networkidle", { timeout: 15_000 }).catch(() => {});

  const cookies = await page.context().cookies();
  const userToken = cookies.find((c) => c.name === "user_token" && c.value);
  expect(userToken, "登录成功后必须下发 user_token cookie").toBeTruthy();
}

export async function logoutUser(page: Page): Promise<void> {
  await page.goto("/user/dashboard/logout").catch(() => {});
  // 兜底：直接清 cookie
  await page.context().clearCookies();
}
