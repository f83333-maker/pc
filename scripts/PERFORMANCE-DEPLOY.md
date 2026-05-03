# 性能优化部署指南

本文档记录 4 批性能优化的部署步骤、验证方法、回滚方案。

---

## 1. 服务器侧部署 (必做)

### 1.1 拉代码

```bash
cd /www/wwwroot/pcccc.cc   # 你的网站根目录
git pull origin main       # 或对应分支
```

### 1.2 重建 JS 双 bundle (招 4)

源文件如果有改动需要重新构建 bundle. 仓库里已经有构建好的 `_vendor_lite.min.js` + `_layui_lite.min.js`,
所以**首次部署不需要构建**, 直接用就行. 之后改了源文件再执行:

```bash
bash scripts/build-index-lite.sh
```

### 1.3 清空模板编译缓存 (必做)

模板改动了, 必须清空编译缓存才能生效:

```bash
rm -rf runtime/view/cache/* runtime/view/compile/*
```

### 1.4 (可选) 关闭 PHP DEBUG, 让 preload + bundle 生效

`config/app.php` 或环境变量里, 确认 `DEBUG = false`. 否则会走 DEBUG 数组 (加载 30+ 个独立 JS).

---

## 2. Cloudflare 后台操作 (强烈建议)

### 2.1 关闭 Rocket Loader (最重要)

> Dashboard → 选 pcccc.cc → Speed → Optimization → Content Optimization → **Rocket Loader: Off**

理由: Rocket Loader 会劫持所有 `<script>`, 改成异步队列执行, 反而拖慢已经加 defer 的现代 bundle.

### 2.2 关闭 Email Address Obfuscation (可选)

> Dashboard → Scrape Shield → **Email Address Obfuscation: Off**

理由: 这功能会自动注入 `email-decode.min.js` 外部脚本, 增加一次额外请求.
我们已经在模板里把 `mailto:` 都换成 `javascript:void(0)`, 关掉这功能就不会再注入了.

### 2.3 关闭开发模式 (Development Mode)

> Dashboard → Caching → Configuration → **Development Mode: Off**

开发模式下所有响应都是 `cf-cache: DYNAMIC`, 不会缓存任何资源. 必须关掉.

### 2.4 设置静态资源永久缓存 (Page Rule 或 Cache Rule)

新版 CF (推荐) - **Cache Rules**:

> Dashboard → Caching → Cache Rules → Create Rule
>
> - **Rule name**: assets-long-cache
> - **When incoming requests match**:
>   - Field: URI Path
>   - Operator: starts with
>   - Value: `/assets/`
> - **Then**:
>   - Cache eligibility: Eligible for cache
>   - Edge TTL: Override origin → 1 month
>   - Browser TTL: Override origin → 1 year

旧版 CF - **Page Rules** (免费版限 3 条):

> URL: `pcccc.cc/assets/*`  
> Settings: Cache Level = Cache Everything, Edge Cache TTL = 1 month, Browser Cache TTL = 1 year

---

## 3. Cloudflare Worker 部署 (招 5, 终极方案)

> 如果你想要国内匿名用户首屏 TTFB 从 ~350ms 降到 ~30ms, 必须做这步.
> 操作大约 3 分钟. CF 免费版每天 100,000 次 Worker 请求免费, 你的访问量绝对够用.

### 3.1 创建 Worker

1. CF Dashboard → 左侧 **Workers & Pages** → **Create application** → **Create Worker**
2. 取名 `pcccc-html-cache` (或任意名字)
3. 点击 **Edit code**, 把 `scripts/cloudflare-worker-html-cache.js` 整个文件内容**全部复制粘贴**进去
4. 点 **Save and deploy**

### 3.2 绑定到 pcccc.cc

5. 部署后回到 Worker 页面 → **Settings** → **Triggers**
6. 点 **Add Custom Domain** (推荐) 或 **Add route**

   - **方案 A (推荐)**: Custom Domain `pcccc.cc` (CF 自动接管所有路径)
   - **方案 B (谨慎测试)**: Route `pcccc.cc/*`, Zone `pcccc.cc`

7. 等 1-2 分钟 DNS 生效

### 3.3 验证 Worker 命中

```bash
# 第一次访问 (回源)
curl -sI "https://pcccc.cc/" | grep -i x-edge-cache
# 期望: x-edge-cache: MISS

# 等 1 秒后再次访问 (边缘命中)
curl -sI "https://pcccc.cc/" | grep -i x-edge-cache
# 期望: x-edge-cache: HIT

# 带 cookie (模拟登录用户) - 应该绕过缓存
curl -sI "https://pcccc.cc/" -H "Cookie: ACG-SHOP=test" | grep -i x-edge-cache
# 期望: 没有 x-edge-cache 头 (走原始流程)
```

### 3.4 失效 (改了首页要立即生效时)

**方案 A**: CF Dashboard → Caching → Configuration → **Purge Everything**

**方案 B**: 改 `cloudflare-worker-html-cache.js` 第 27 行的 `CACHE_VERSION = "v1"` 为 `"v2"`, 重新部署 Worker, 所有旧缓存秒失效.

### 3.5 出问题怎么回滚

1. CF Dashboard → Workers & Pages → 选中 worker → Settings → Triggers
2. 删除 Custom Domain / Route
3. 网站立刻恢复无 Worker 状态

---

## 4. 验证整体效果

部署完成后, 在国内运营商测速 (ping.chinaz.com / itdog.cn) 对比:

| 指标 | 优化前 | 期望 |
|---|---|---|
| 首屏 TTFB (匿名访客) | ~350 ms | ~30 ms (Worker 命中后) |
| HTML 大小 | 4.7 KB (br) | 4.5 KB (br) |
| 首屏 JS 总量 | 533 KB (gz) | 199 KB (gz, 双 bundle 并行) |
| 首屏 CSS 请求数 | 10 | 3 |
| 首屏总下载 | ~470 KB | ~280 KB |
| 国内全程加载 | 9-10 s | 3-4 s (CF Worker + 双 bundle 并行) |

如果国内速度还在 5 秒以上, 说明 CF 免费版国内带宽就是这么差, 已经到代码层极限,
下一步**只能换 CDN** (推荐: 腾讯云 EdgeOne 国际版, 不需要备案).

---

## 5. 常见问题

**Q: Worker 部署后某些页面打不开了?**
A: 立即按 3.5 节回滚. 然后查看 worker 的 Logs (CF Dashboard → 选中 worker → Logs → Begin log stream).

**Q: 改了商品价格但首页还是显示旧价?**
A: 商品列表是异步从 `/user/api/index/commodity` 拉的, 不在 HTML 缓存范围内, 不应该有这问题. 如果有, 可能是浏览器自己的缓存, 强制刷新 Ctrl+F5.

**Q: 改了首页公告但没生效?**
A: 公告是 SSR 在 HTML 里的, 受 60s 缓存影响. 等 60s 或按 3.4 节立即失效.

**Q: 备案过的 .com 域名想接入国内 CDN?**
A: 联系我, 我帮你做"CDN 域名切换层", 把 `/assets/*` 通过环境变量切到国内 CDN, HTML 留 CF Worker 加速.
