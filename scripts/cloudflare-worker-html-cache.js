/**
 * Cloudflare Worker: HTML 边缘缓存 (匿名用户首页加速)
 *
 * 目的: 让匿名访客的首页/公告/查单等公开页面在 CF 边缘节点直接命中,
 *       国内 TTFB 从 ~350ms 降到 ~30ms (省去回源到你服务器的整个海外往返).
 *
 * v2 更新:
 *   - 强制剥离 Set-Cookie / Vary (caches.default 见到这两个头会拒绝缓存)
 *   - 增加 X-Cache-Debug 头, 出问题时一眼看出走的哪条分支
 *   - 增加 X-Cache-Colo 头, 区分不同 CF 数据中心 (排查跨节点缓存问题)
 *
 * 部署方式:
 *   1. CF Dashboard -> Workers & Pages -> pcccc-html-cache -> Edit code
 *   2. 全选删除老代码, 把本文件全部内容粘贴进去
 *   3. 点 "部署"
 *   4. 路由保持 pcccc.cc/* 不变
 *
 * 缓存策略:
 *   - 只缓存 GET 请求, 且 URL 在 CACHEABLE_PATHS 白名单里
 *   - 已登录 (带 ACG-SHOP cookie) 用户直接回源, 不缓存
 *   - 命中后边缘 60s 内直接返回
 *
 * 失效:
 *   - 改了首页内容? 在 CF 后台 -> 缓存 -> 配置 -> 清除全部
 *   - 或在 Worker 里把 CACHE_VERSION 数字加 1 重新部署 (秒级失效)
 */

const CACHE_VERSION = "v2";          // 改这个数字可让所有缓存立即失效
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
    const colo = request.cf && request.cf.colo ? request.cf.colo : "unknown";

    // 只处理 GET, 其它方法直接放行
    if (request.method !== "GET") {
        return tagBypass(await fetch(request), "non-get", colo);
    }

    // 不在白名单 -> 直接回源
    if (!isCacheable(url)) {
        return tagBypass(await fetch(request), "path-not-whitelisted", colo);
    }

    // 带用户 cookie -> 直接回源, 不缓存私人内容
    const cookie = request.headers.get("Cookie") || "";
    if (USER_COOKIES.some(name => cookie.includes(name + "="))) {
        return tagBypass(await fetch(request), "user-cookie", colo);
    }

    // 带 query string (如 ?categoryId=1) 不缓存, 避免缓存爆炸
    if (url.search) {
        return tagBypass(await fetch(request), "has-query", colo);
    }

    // 构造缓存 key (不带 cookie, 仅按 URL + 版本)
    const cacheKey = new Request(
        url.origin + url.pathname + "?_v=" + CACHE_VERSION,
        { method: "GET" }
    );
    const cache = caches.default;

    // 1. 命中边缘缓存
    let cached = await cache.match(cacheKey);
    if (cached) {
        const out = new Response(cached.body, cached);
        out.headers.set("X-Edge-Cache", "HIT");
        out.headers.set("X-Cache-Debug", "edge-hit");
        out.headers.set("X-Cache-Colo", colo);
        return out;
    }

    // 2. 未命中 -> 回源 -> 同时写入缓存
    const originResponse = await fetch(request);
    const contentType = originResponse.headers.get("Content-Type") || "";

    // 只缓存 200 + HTML
    if (originResponse.status !== 200 || !contentType.includes("text/html")) {
        return tagBypass(originResponse, `not-cacheable-status-${originResponse.status}`, colo);
    }

    // 关键: 必须把 Set-Cookie / Vary 等阻止 caches.default 缓存的头剥掉, 再写入缓存.
    // 但又要让用户拿到原始的 Set-Cookie (如果有), 所以用两份 Response.
    const userResponse = originResponse.clone();
    const cacheable = new Response(originResponse.body, originResponse);

    // 清除阻止缓存的头
    cacheable.headers.delete("Set-Cookie");
    cacheable.headers.delete("Vary");
    cacheable.headers.delete("Pragma");
    cacheable.headers.delete("Expires");

    // 强制可缓存的 Cache-Control (覆盖源站的 no-store 之类)
    cacheable.headers.set(
        "Cache-Control",
        `public, max-age=${EDGE_TTL}, s-maxage=${EDGE_TTL}, stale-while-revalidate=${STALE_TTL}`
    );
    cacheable.headers.set("X-Edge-Cache", "MISS");
    cacheable.headers.set("X-Cache-Debug", "origin-fetched-and-stored");
    cacheable.headers.set("X-Cache-Colo", colo);

    // event.waitUntil 让 cache.put 在后台执行, 不阻塞响应
    event.waitUntil(cache.put(cacheKey, cacheable.clone()));

    // 返回给用户的 Response 也带上调试头
    const finalUserResponse = new Response(userResponse.body, userResponse);
    finalUserResponse.headers.set("X-Edge-Cache", "MISS");
    finalUserResponse.headers.set("X-Cache-Debug", "origin-fetched-and-stored");
    finalUserResponse.headers.set("X-Cache-Colo", colo);
    return finalUserResponse;
}

function isCacheable(url) {
    return CACHEABLE_PATHS.includes(url.pathname);
}

function tagBypass(response, reason, colo) {
    const out = new Response(response.body, response);
    out.headers.set("X-Edge-Cache", "BYPASS");
    out.headers.set("X-Cache-Debug", reason);
    out.headers.set("X-Cache-Colo", colo);
    return out;
}
