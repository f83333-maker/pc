# 网站现有图片批量转 WebP（自适应交付方案）

## 设计原则

| 项目 | 做法 |
| --- | --- |
| 原始图片 | **完全保留**，不删除 |
| WebP 文件 | 与原图同目录、同文件名（仅扩展名不同） |
| 数据库 | **零修改**（路径还是 `.png` / `.jpg`） |
| PHP 代码 | **零修改** |
| URL | 不变（浏览器看到的还是 `xxx.png`） |
| 内容协商 | Nginx 根据浏览器 `Accept` 头自动选择回 `.png` 还是 `.webp` |
| 不支持 WebP 的客户端 | 自动回退到原图（老 IE / 部分爬虫） |

---

## 部署步骤（在你 Ubuntu 服务器上执行）

### 1. 拉取最新代码

```bash
cd /www/wwwroot/pcccc.cc
git pull origin main
```

应看到新增 `scripts/convert-images-to-webp.sh`、`scripts/nginx-webp.conf`、`scripts/README-webp.md`。

### 2. 给脚本加可执行权限并预演

```bash
chmod +x scripts/convert-images-to-webp.sh

# 先预演一次，看看会转换多少张图
bash scripts/convert-images-to-webp.sh --dry-run
```

输出会告诉你"发现 X 张图片"。如果数量符合预期（比如几百到几万张），继续。

### 3. 正式批量转换

```bash
bash scripts/convert-images-to-webp.sh
```

第一次运行会自动 `apt-get install webp`（需要 root 或 sudo）。

转换过程会显示进度，结束后给一个汇总报告，例如：

```
==========================================
  转换完成
==========================================
  发现图片:        2456
  成功转换:        2103
  已存在跳过:      0
  尺寸过小跳过:    341
  转后变大丢弃:    12
  原始总大小:      1245.83 MB
  节省空间:        687.41 MB (55.2%)
```

> 节省空间是**额外占用**的磁盘量（因为保留了原图），不是绝对节省。但浏览器加载时会自动用更小的 WebP，访客流量节省 50%~70%。

### 4. 配置 Nginx 自适应交付

打开宝塔面板 → 网站 → pcccc.cc → 设置 → **配置文件**。

把 `scripts/nginx-webp.conf` 里的两段配置加到 `server { ... }` 内，**注意 map 指令的位置**：

#### 方式 A（最简单，推荐）：直接放进 server {} 内

```nginx
server {
    listen 80;
    server_name pcccc.cc;

    # ↓ ↓ ↓ 新增 ↓ ↓ ↓
    map $http_accept $webp_suffix {
        default   "";
        "~*image/webp" ".webp";
    }
    
    location ~* ^/assets/cache/.+\.(png|jpe?g)$ {
        add_header Vary "Accept";
        add_header Cache-Control "public, max-age=31536000, immutable";
        add_header CDN-Cache-Control "public, max-age=31536000, must-revalidate";
        expires 1y;
        access_log off;
        try_files $uri$webp_suffix $uri =404;
    }
    # ↑ ↑ ↑ 新增 ↑ ↑ ↑

    # ... 你之前已有的配置（伪静态、长缓存等）保持不动 ...
}
```

> 部分老版 Nginx 不允许 map 出现在 server 块内。如果保存时报错 `"map" directive is not allowed here`，就把 map 这一段挪到 `/www/server/nginx/conf/nginx.conf` 的 `http {}` 块里（保留 location 块在 server 内）。

保存后**宝塔会自动测试 Nginx 配置**，如果有语法错误它会拒绝保存——这一步是安全的。

### 5. 验证 WebP 自适应已生效

在你电脑上执行：

```bash
# 模拟支持 WebP 的浏览器请求
curl -sI -H "Accept: image/webp,image/*" https://pcccc.cc/assets/cache/general/image/202605012123418043414.png | grep -iE "(content-type|content-length|vary)"
```

应该返回：

```
content-type: image/webp        ← 注意是 image/webp 而不是 image/png
vary: Accept
content-length: 4231            ← 比原图小很多
```

再模拟不支持 WebP 的客户端：

```bash
curl -sI -H "Accept: image/png" https://pcccc.cc/assets/cache/general/image/202605012123418043414.png | grep -iE "(content-type|content-length)"
```

应该返回：

```
content-type: image/png         ← 原图
content-length: 19942
```

两个测试都对，说明自适应交付已经生效。

### 6. 清空 Cloudflare 缓存

Cloudflare → pcccc.cc → 缓存 → 配置 → **清除所有内容**。

> 因为我们设置了 `Vary: Accept` 头，Cloudflare 会按 Accept 头分别缓存 .png / .webp 两份，回头客访问会自动命中正确版本。

### 7. 浏览器实测

打开 https://pcccc.cc/，F12 → Network 标签 → 刷新 → 找一张图片 → 看 **Type** 列：

- 如果显示 **webp**，说明已经在用 WebP（虽然 URL 还是 `.png`）
- Size 列也会比原来小很多

---

## 常见问题

### Q1: 我后台再上传新图片，还会自动转 WebP 吗？

**会。** 之前已经做过的 `webp-converter.js` 会在浏览器端把上传文件转成 WebP 再提交，PHP 直接保存 `.webp` 文件。所以**新上传的图片是 .webp 文件 + 数据库存 .webp 路径**，这次脚本是**追加**为旧的 .png/.jpg 也生成一份 .webp 副本，让旧图也能享受 WebP 的体积红利。

### Q2: Cloudflare 后续要不要也开 Polish？

**不需要重复开。** 你现在已经在源站层面做了完整的 WebP 转换 + 自适应交付，效果等同 Polish。如果你之后开了 Cloudflare Polish，它会再压一次（无害但意义不大）。

### Q3: 怎么回滚？

直接在 Nginx 配置里**删掉**新增的 map 和 location 块即可。原 .png/.jpg 文件都还在，URL 依然有效，零损失。

```bash
# 如果觉得 .webp 副本占磁盘空间太多, 可以一键删掉所有 .webp（保留原图）
find /www/wwwroot/pcccc.cc/assets/cache -type f -name "*.webp" -delete
```

### Q4: 这次转换失败/中断怎么办？

直接重跑 `bash scripts/convert-images-to-webp.sh` 即可。脚本会自动跳过已经存在 .webp 的图片（断点续传）。

如果想强制重新转换某些图（比如调高 quality 重新生成）：

```bash
bash scripts/convert-images-to-webp.sh --force --quality=90
```

### Q5: 我想看看具体节省了多少？

转换完成后，看脚本输出的"节省空间 XXX MB（XX%）"。

```bash
# 也可以单独查看 webp 副本占了多少磁盘
find /www/wwwroot/pcccc.cc/assets/cache -type f -name "*.webp" | xargs du -ch | tail -1
```

---

## 总结

执行完这 7 步后：

- **磁盘占用**：增加约 30-50%（因为保留了原图 + 新增 .webp，但 .webp 比原图小）
- **访客流量**：减少 50-70%（取决于图片类型，照片类节省更多）
- **加载速度**：图片型页面 LCP 提升 30-50%
- **风险**：极低，原图全部保留，URL 不变，老浏览器自动回退
