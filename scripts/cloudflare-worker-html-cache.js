/**
 * Cloudflare Worker: HTML 边缘缓存 (匿名用户首页加速)
 *
 * 目的: 让匿名访客的首页/公告/查单等公开页面在 CF 边缘节点直接命中,
 *       国内 TTFB 从 ~350ms 降到 ~30ms (省去回源到你服务器的整个海外往返).
 *
 * 部署方式:
 *   1. CF Dashboard -> Workers & Pages -> Create Application -> Create Worker
 *   2. 命名为 pcccc-html-cache, 把本文件全部内容粘贴到 worker.js, 部署
 *   3. 选中 Worker -> Settings -> Triggers -> Custom Domains 或 Routes
 *      Route: pcccc.cc/*  Zone: pcccc.cc
 *      (强烈建议先用 Routes 测试, 等稳定再切 Custom Domain)
 *
 * 缓存策略:
 *   - 只缓存 GET 请求, 且 URL 在 CACHEABLE_PATHS 白名单里
 *   - 已登录 (带 ACG-SHOP cookie) 用户直接回源, 不缓存
 *   - 命中后边缘 60s 内直接返回, 同时后台异步刷新 (stale-while-revalidate 思路)
 *   - 不影响所有 /user/api/* /admin/* POST 等动态请求
 *
 * 安全检查:
 *   - 缓存的 HTML 不含 csrf_token (本项目 csrf 走 cookie + header 不在 HTML 里)
 *   - 缓存的 HTML 不含个人数据 (匿名用户访问时这些占位本来就是空的)
 *
 * 失效:
 *   - 改了首页内容? 在 CF 后台 -> Caching -> Configuration -> Purge Everything
 *   - 或在 Worker 里把 CACHE_VERSION 数字加 1 重新部署 (秒级失效)
 */

const CACHE_VERSION = "v1";          // 改这个数字可让所有缓存立即失效
const EDGE_TTL = 60;                 // 边缘缓存秒数, 60s 平衡新鲜度和命中率
const STALE_TTL = 600;               // 过期后还可以用 stale 多久 (后台异步刷新)

// 白名单: 只缓存这些公开页面 (其它一律回源)
const CACHEABLE_PATHS = [
    "/",                  // 首页 (商品列表本身是 API 异步加载, HTML 是骨架)
    "/user/index/query",  // 订单查询页 (输入订单号才查, HTML 是空表单)
    "/user/index/twofa"   // 2FA 验证页 (HTML 是空表单)
];

// 这些 cookie 出现就视为"已登录或有用户态", 直接绕过缓存
const USER_COOKIES = ["ACG-SHOP", "client_id"];

addEventListener("fetch", event => {
    event.respondWith(handle(event));
});

async function handle(event) {
    const request = event.request;
    const url = new URL(request.url);

    // 只处理 GET, 其它方法直接放行
    if (request.method !== "GET") {
        return fetch(request);
    }

    // 不在白名单 -> 直接回源
    if (!isCacheable(url)) {
        return fetch(request);
    }

    // 带用户 cookie -> 直接回源, 不缓存私人内容
    const cookie = request.headers.get("Cookie") || "";
    if (USER_COOKIES.some(name => cookie.includes(name + "="))) {
        return fetch(request);
    }

    // 带 query string (如 ?categoryId=1) 不缓存, 避免缓存爆炸
    if (url.search) {
        return fetch(request);
    }

    // 构造缓存 key (不带 cookie, 仅按 URL + 版本)
    const cacheKey = new Request(
        url.origin + url.pathname + "?_v=" + CACHE_VERSION,
        { method: "GET" }
    );
    const cache = caches.default;

    // 1. 命中边缘缓存
    let response = await cache.match(cacheKey);
    if (response) {
        // 加个标记方便排查
        response = new Response(response.body, response);
        response.headers.set("X-Edge-Cache", "HIT");
        return response;
    }

    // 2. 未命中 -> 回源 -> 同时写入缓存
    response = await fetch(request);

    // 只缓存 200 + HTML, 避免把 5xx/重定向缓存进去
    const contentType = response.headers.get("Content-Type") || "";
    if (response.status === 200 && contentType.includes("text/html")) {
        // clone 一份给缓存, 一份返回给用户
        const cacheable = new Response(response.body, response);
        cacheable.headers.set(
            "Cache-Control",
            `public, max-age=${EDGE_TTL}, s-maxage=${EDGE_TTL}, stale-while-revalidate=${STALE_TTL}`
        );
        cacheable.headers.set("X-Edge-Cache", "MISS");
        // event.waitUntil 让 cache.put 在后台执行, 不阻塞响应
        event.waitUntil(cache.put(cacheKey, cacheable.clone()));
        return cacheable;
    }

    return response;
}

function isCacheable(url) {
    return CACHEABLE_PATHS.includes(url.pathname);
}
