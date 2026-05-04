import { Page, expect } from "@playwright/test";
import { ADMIN } from "./data";

/**
 * 通过浏览器 UI 登录后台
 *
 * 必须走 UI——后端 POST /admin 经过 PostDecrypt 拦截器，
 * body 必须 AES + Signature 加密；只有让 base.js 在浏览器里执行加密才能登录。
 */
export async function loginAdmin(page: Page): Promise<void> {
  await page.goto("/admin");

  // 用户名 / 密码字段在不同主题下命名不一，按属性容错查找
  const usernameInput = page
    .locator('input[name="username"], input[name="account"], input[type="text"]:not([name="captcha"])')
    .first();
  const passwordInput = page.locator('input[type="password"]').first();

  await usernameInput.fill(ADMIN.username);
  await passwordInput.fill(ADMIN.password);

  // 验证码（如启用）：本地调试可设置 MANUAL_CAPTCHA=1 暂停人工输入
  const captcha = page.locator('input[name="captcha"], input[name="code"]');
  if ((await captcha.count()) > 0 && process.env.MANUAL_CAPTCHA === "1") {
    console.log("[v0] 检测到验证码，请手动输入后回车继续...");
    await page.pause();
  }

  const submit = page
    .locator('button[type="submit"], button:has-text("登录"), button:has-text("登 录")')
    .first();

  await Promise.all([
    page.waitForURL(/\/admin\/dashboard/, { timeout: 30_000 }).catch(() => {}),
    submit.click(),
  ]);

  // 给 SPA 跳转一点时间
  await page.waitForLoadState("networkidle", { timeout: 15_000 }).catch(() => {});

  await expect(page, "登录后应进入 /admin/dashboard").toHaveURL(/\/admin\/dashboard/, {
    timeout: 15_000,
  });
}

/** 退出后台登录，确保后续测试干净 */
export async function logoutAdmin(page: Page): Promise<void> {
  await page.goto("/admin/personal/logout").catch(() => {});
}
