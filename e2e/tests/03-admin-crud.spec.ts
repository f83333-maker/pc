import { test, expect } from "@playwright/test";
import { loginAdmin, logoutAdmin } from "../helpers/admin-login";
import { TS } from "../helpers/data";

/**
 * 阶段 3-2：后台深度交互（破坏性 CRUD）
 *
 * 串行执行（fullyParallel=false + workers=1），用唯一 TS 标签隔离测试数据，
 * 用例自身负责 cleanup（删除测试创建的实体）。
 */
test.describe.serial("后台 CRUD 全流程", () => {
  test.beforeEach(async ({ page }) => {
    await loginAdmin(page);
  });

  test.afterAll(async ({ browser }) => {
    const ctx = await browser.newContext();
    const page = await ctx.newPage();
    await logoutAdmin(page).catch(() => {});
    await ctx.close();
  });

  test("Dashboard 页面加载正常", async ({ page }) => {
    await page.goto("/admin/dashboard");
    await expect(page).toHaveURL(/\/admin\/dashboard/);
    await expect(page.locator("body")).toContainText(
      /统计|订单|用户|今日|管理|系统/,
    );
  });

  test("CRUD: 商品分类（创建 → 修改 → 删除）", async ({ page }) => {
    const NAME = `e2e_cat_${TS}`;
    const NEW_NAME = `${NAME}_renamed`;

    // ---------- 1. 创建 ----------
    await page.goto("/admin/shop/category");
    await page.waitForLoadState("networkidle").catch(() => {});

    const addBtn = page
      .locator(
        'button:has-text("添加"), button:has-text("新增"), button:has-text("新 增")',
      )
      .first();
    await addBtn.click();

    const nameInput = page.locator('input[name="name"]').first();
    await expect(nameInput).toBeVisible({ timeout: 10_000 });
    await nameInput.fill(NAME);

    const saveBtn = page
      .locator(
        'button:has-text("保存"), button:has-text("提 交"), button:has-text("提交"), button:has-text("确 定"), button:has-text("确定"), button[type="submit"]',
      )
      .first();
    await saveBtn.click();

    await page.waitForTimeout(1500);
    await page.reload();
    await expect(page.locator("body"), "新建分类应出现在列表").toContainText(NAME);

    // ---------- 2. 修改 ----------
    const editRow = page.locator(`tr:has-text("${NAME}")`).first();
    await editRow
      .locator('a:has-text("编辑"), button:has-text("编辑")')
      .first()
      .click();

    const editInput = page.locator('input[name="name"]').first();
    await editInput.fill("");
    await editInput.fill(NEW_NAME);
    await page
      .locator(
        'button:has-text("保存"), button:has-text("提 交"), button:has-text("提交")',
      )
      .first()
      .click();

    await page.waitForTimeout(1500);
    await page.reload();
    await expect(page.locator("body"), "改名后列表应可见").toContainText(NEW_NAME);

    // ---------- 3. 删除（cleanup）----------
    const delRow = page.locator(`tr:has-text("${NEW_NAME}")`).first();
    page.once("dialog", (d) => d.accept());
    await delRow
      .locator('a:has-text("删除"), button:has-text("删除")')
      .first()
      .click();

    // layui 自定义 confirm 弹窗
    const layuiConfirm = page
      .locator('.layui-layer-btn0:has-text("确定"), .layui-layer-btn0')
      .first();
    if (await layuiConfirm.count()) await layuiConfirm.click();

    await page.waitForTimeout(2000);
    await page.reload();
    await expect(
      page.locator("body"),
      "删除后列表不应再包含该名称",
    ).not.toContainText(NEW_NAME);
  });

  test("用户管理：调整测试账号余额（破坏性）后回滚", async ({ page }) => {
    await page.goto("/admin/user");
    await page.waitForLoadState("networkidle").catch(() => {});

    const search = page
      .locator('input[name="username"], input[placeholder*="用户名"], input[placeholder*="账号"]')
      .first();
    if (await search.count()) {
      await search.fill("222222");
      await page
        .locator('button:has-text("搜索"), button:has-text("查询"), button[type="submit"]')
        .first()
        .click()
        .catch(() => {});
      await page.waitForTimeout(1200);
    }

    const row = page.locator('tr:has-text("222222")').first();
    if (!(await row.count())) {
      test.skip(true, "未找到测试用户 222222（可能不在第一页或被禁用）");
    }

    // 调整 +0.01
    const adjBtn = row
      .locator(
        'button:has-text("余额"), a:has-text("余额"), button:has-text("调整"), a:has-text("调整")',
      )
      .first();
    if (!(await adjBtn.count())) test.skip(true, "未找到余额调整入口");
    await adjBtn.click();

    const amountInput = page
      .locator('input[name="amount"], input[type="number"]')
      .first();
    await amountInput.fill("0.01");
    await page
      .locator('input[name="remark"], textarea[name="remark"]')
      .first()
      .fill(`E2E_${TS}_+`)
      .catch(() => {});

    await page
      .locator('button:has-text("确定"), button:has-text("提交"), button[type="submit"]')
      .first()
      .click();

    await expect(
      page.getByText(/成功|完成|更新成功/).first(),
      "余额调整成功提示",
    ).toBeVisible({ timeout: 10_000 });

    // 立即回滚 -0.01，避免污染数据
    await page.waitForTimeout(800);
    await page.reload();
    const rowBack = page.locator('tr:has-text("222222")').first();
    await rowBack
      .locator(
        'button:has-text("余额"), a:has-text("余额"), button:has-text("调整"), a:has-text("调整")',
      )
      .first()
      .click()
      .catch(() => {});
    await page
      .locator('input[name="amount"], input[type="number"]')
      .first()
      .fill("-0.01")
      .catch(() => {});
    await page
      .locator('button:has-text("确定"), button:has-text("提交"), button[type="submit"]')
      .first()
      .click()
      .catch(() => {});
  });

  test("退出登录后受保护路径应被重定向", async ({ page }) => {
    await page.goto("/admin/personal/logout").catch(() => {});
    await page.waitForTimeout(500);
    await page.goto("/admin/dashboard");
    // 退出后访问 dashboard 应跳到登录页（路径仍是 /admin 但要求填密码）
    await expect(page.locator('input[type="password"]').first()).toBeVisible({
      timeout: 10_000,
    });
  });
});
