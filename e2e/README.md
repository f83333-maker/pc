# pcccc.cc 全链路 E2E 测试套件

基于 Playwright + TypeScript，针对线上 [https://pcccc.cc](https://pcccc.cc) 设计的端到端深度测试。

## 为什么必须用 UI 自动化而不是直接 fetch

后端所有 POST API 都过 `App\Interceptor\PostDecrypt` 拦截器，body 必须满足：

- 经 AES-CBC 加密（key/iv 取 `Secret` 头前 16 字节）
- 携带 `Signature` 头 = `MD5(ksort(post)拼接 + secret)`

签名算法在前端 `assets/common/js/base.js` 中实现。脚本通过浏览器加载页面、让原生 JS 完成加密后再触发，避免在 Node 端复刻一遍签名逻辑（会随框架升级失效）。

## 安装

```bash
cd e2e
npm install
npx playwright install chromium
```

## 运行

```bash
# 默认指向 https://pcccc.cc
npm test

# 指向其他环境
BASE_URL=https://staging.pcccc.cc npm test

# 仅烟测（连通性 + SEO）
npm run test:smoke

# 破坏性 CRUD + 注入
npm run test:destructive

# 业务流 + 越权
npm run test:flow

# 可视化排错
npm run test:ui
npm run test:headed
```

## 凭据覆写

```bash
ADMIN_USERNAME='9@9.9' ADMIN_PASSWORD='Aa199793' \
USER_USERNAME='222222' USER_PASSWORD='222222' \
npm test
```

## 文件结构

```
e2e/
├── playwright.config.ts          # 配置（baseURL / 串行 / 截图 / trace）
├── helpers/
│   ├── data.ts                   # 凭据 / 攻击 payload
│   ├── admin-login.ts            # 后台登录复用
│   └── user-login.ts             # 会员登录复用
└── tests/
    ├── 01-connectivity.spec.ts        # 公开页可达 / robots / sitemap / 404
    ├── 02-seo.spec.ts                 # canonical / OG / JSON-LD / alt / noindex
    ├── 03-admin-crud.spec.ts          # 后台分类增删改 + 余额调整回滚
    ├── 04-injection-xss.spec.ts       # SQL 注入 / XSS / 旁路签名
    ├── 05-user-purchase-flow.spec.ts        # 加购 → 结算 → 订单
    ├── 06-authorization-concurrency.spec.ts # 越权 / token 篡改 / 狂点 / 双 Tab
    ├── 07-order-authorization-regression.spec.ts # 第二轮：B3/B4/cancel 越权回归
    ├── 08-balance-transfer-atomicity.spec.ts     # 第二轮：余额转账原子性 / 并发
    └── 09-deploy-hardening.spec.ts               # 第二轮：/install 锁 / 敏感文件 / SEO 修复落地
```

## 第二轮安全修复回归（2026-05）

代码层面修复对应的回归脚本（验证修复在线上真的生效）：

| Spec | 对应修复 | 期望线上行为 |
|---|---|---|
| `07-order-authorization-regression` | `Order::getOrder/downloadOrder/cancel` 加 `assertOrderOwnership` | 用伪造 trade_no 调用必须返回"订单不存在"，绝不能吐 treasure |
| `08-balance-transfer-atomicity` | `Balance::transfer` 包 `Db::transaction(SERIALIZABLE)` | 转账并发狂点不出现 5xx，余额永远 ≥ 0 |
| `09-deploy-hardening` | `PostDecrypt` 空 body 旁路 + `/install` 锁 + canonical/sitemap 短链 | 旁路签名失败、敏感文件不外泄、SEO canonical 已切短链 |

## 注意事项

1. **串行执行**：`workers=1`，因为 CRUD 和余额调整不能并发污染共享数据。
2. **数据清理**：每个 CRUD 用例自身负责删除测试创建的实体。
3. **soft 断言**：图片 alt、内链、响应头基线用 `expect.soft` 不阻塞主流程。
4. **失败截图与 trace**：默认在 `test-results/` 与 `playwright-report/` 下，可用 `npm run test:report` 打开。
5. **手动验证码**：`MANUAL_CAPTCHA=1 npm test` 会在登录页 `page.pause()`，便于手动输码。
