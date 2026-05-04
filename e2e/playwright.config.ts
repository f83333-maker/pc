import { defineConfig, devices } from "@playwright/test";

/**
 * Playwright 端到端配置
 *
 * 默认运行于 https://pcccc.cc，可通过 BASE_URL 环境变量覆盖：
 *   BASE_URL=https://staging.pcccc.cc npx playwright test
 *
 * 由于后台 CRUD 是破坏性测试，强制 workers=1 串行执行避免数据竞争。
 */
export default defineConfig({
  testDir: "./tests",
  fullyParallel: false,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 1 : 0,
  workers: 1,
  reporter: [
    ["html", { open: "never" }],
    ["list"],
  ],
  timeout: 60_000,
  expect: { timeout: 10_000 },
  use: {
    baseURL: process.env.BASE_URL || "https://pcccc.cc",
    trace: "retain-on-failure",
    screenshot: "only-on-failure",
    video: "retain-on-failure",
    ignoreHTTPSErrors: true,
    actionTimeout: 15_000,
    navigationTimeout: 30_000,
    locale: "zh-CN",
    timezoneId: "Asia/Shanghai",
    extraHTTPHeaders: {
      "Accept-Language": "zh-CN,zh;q=0.9",
    },
  },
  projects: [
    {
      name: "chromium",
      use: { ...devices["Desktop Chrome"] },
    },
  ],
});
