import { test, expect } from "@playwright/test";

test("调试：检查登录后 cookies", async ({ page }) => {
  await page.goto("/user/authentication/login");
  
  // 填写表单
  await page.locator('input[name="username"]').first().fill("222222");
  await page.locator('input[type="password"]').first().fill("222222");
  
  // 检查验证码
  const captcha = page.locator('input[name="captcha"], #image-captcha');
  const hasCaptcha = await captcha.count() > 0;
  console.log("[v0] 验证码存在:", hasCaptcha);
  
  if (hasCaptcha) {
    const visible = await captcha.first().isVisible().catch(() => false);
    console.log("[v0] 验证码可见:", visible);
    if (visible) {
      // 盲填
      await captcha.first().fill("0000");
    }
  }
  
  // 提交
  await page.locator('button[type="submit"]').first().click();
  await page.waitForTimeout(3000);
  await page.waitForLoadState("networkidle").catch(() => {});
  
  // 打印所有 cookies
  const cookies = await page.context().cookies();
  console.log("[v0] 所有 cookies:", JSON.stringify(cookies.map(c => ({name: c.name, value: c.value.substring(0,30)})), null, 2));
  
  // 打印当前 URL
  console.log("[v0] 当前 URL:", page.url());
});
