/**
 * scale-layout.js
 *
 * 移动端等比缩放适配器（transform:scale 方案）
 * ----------------------------------------------------------------
 * 设计目标：在所有移动设备/平台/浏览器上，让页面像一张 DESIGN_WIDTH 像素宽
 * 的"图"等比缩放到设备宽度，跨平台像素级一致——不触发任何 CSS 媒体查询折行
 * /重排，不依赖 vh/vw 等设备相关单位。
 *
 * 工作原理：
 *   1. 仅当满足三项条件（粗糙指针 + 触屏 + screen 短边 < 设计宽）时介入。
 *   2. 把 viewport 锁定为 width=device-width, initial-scale=1（不让浏览器
 *      做二次缩放）。
 *   3. 注入 <style>，把 <body> 强制设为 DESIGN_WIDTH 像素宽，并施加
 *      transform: scale(deviceWidth / DESIGN_WIDTH); transform-origin: top left;
 *   4. 用 ResizeObserver 监听 body 自然高度变化，给 body 施加负 margin-bottom
 *      把 layout 高度补偿到 visual 高度（h * ratio），消除底部空白。
 *   5. orientationchange / resize / pageshow(BFCache) 自动重算。
 *
 * 与传统 viewport 改写方案的区别：
 *   - viewport 方案在 iOS Safari 上 vh/100% 等单位会因平台差异表现不一致；
 *   - transform:scale 方案让所有 vh/vw/媒体查询都按设计宽计算，跨平台一致。
 *
 * 已知 trade-off：
 *   - body 的 background-attachment:fixed 在被 transform 的元素内会降级为
 *     scroll（CSS 规范行为），背景图变成跟随滚动而不是 viewport 固定。
 *
 * 配置：
 *   - window.__SCALE_DESIGN_WIDTH__ = <数字>  在脚本之前设置可覆盖默认值（801）
 *   - window.__DISABLE_SCALE_LAYOUT__ = true  在脚本之前设置可禁用
 *
 * 注意：本脚本必须在 <head> 中同步加载，且尽可能早执行。生产环境直接内联
 *      到 Header.html 即可，本文件保留作同源副本供参考。
 */
(function () {
    'use strict';

    if (window.__DISABLE_SCALE_LAYOUT__ === true) {
        return;
    }

    var DESIGN_WIDTH = (typeof window.__SCALE_DESIGN_WIDTH__ === 'number'
        && window.__SCALE_DESIGN_WIDTH__ > 0)
        ? window.__SCALE_DESIGN_WIDTH__
        : 801;

    function isMobile() {
        try {
            var coarse = window.matchMedia
                && window.matchMedia('(pointer: coarse)').matches;
            var touch = ('ontouchstart' in window)
                || ((navigator.maxTouchPoints || 0) > 0);
            var sw = (window.screen && window.screen.width) || 9999;
            var sh = (window.screen && window.screen.height) || 9999;
            var minSide = Math.min(sw, sh);
            return !!coarse && !!touch && minSide < DESIGN_WIDTH;
        } catch (e) {
            return false;
        }
    }

    var de = document.documentElement;
    if (!isMobile()) {
        de.removeAttribute('data-scale-layout');
        return;
    }

    // Step 1: 锁定 viewport，避免浏览器自身做二次缩放
    var meta = document.querySelector('meta[name="viewport"]');
    if (!meta) {
        meta = document.createElement('meta');
        meta.setAttribute('name', 'viewport');
        document.head.appendChild(meta);
    }
    meta.setAttribute('content',
        'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover');

    function deviceWidth() {
        var sw = (window.screen && window.screen.width) || 0;
        var sh = (window.screen && window.screen.height) || 0;
        var ow = window.orientation;
        var w = (ow === 90 || ow === -90) ? Math.max(sw, sh) : Math.min(sw, sh);
        var cw = document.documentElement.clientWidth || 0;
        // 当 clientWidth 已生效且与 screen 宽差异较大时，以 clientWidth 为准
        if (cw > 0 && cw < DESIGN_WIDTH && Math.abs(cw - w) > w * 0.5) return cw;
        return w || cw;
    }

    function computeRatio() {
        var w = deviceWidth();
        if (!w) return 1;
        var r = w / DESIGN_WIDTH;
        return r >= 1 ? 1 : Math.round(r * 10000) / 10000;
    }

    var ratio = computeRatio();

    // Step 2: 注入样式（在 body 解析时立即生效，避免首屏 DESIGN_WIDTH 宽度溢出）
    var styleEl = document.getElementById('__scale_layout_style__');
    if (!styleEl) {
        styleEl = document.createElement('style');
        styleEl.id = '__scale_layout_style__';
        document.head.appendChild(styleEl);
    }

    function buildCss(r) {
        return 'html{overflow-x:hidden!important;}'
            + 'html,body{margin:0!important;padding:0!important;}'
            + 'body{width:' + DESIGN_WIDTH + 'px!important;'
            + 'transform-origin:top left!important;'
            + 'transform:scale(' + r + ')!important;}';
    }

    styleEl.textContent = buildCss(ratio);

    de.setAttribute('data-scale-layout', 'mobile');
    de.setAttribute('data-scale-base', String(DESIGN_WIDTH));
    de.setAttribute('data-scale-ratio', String(ratio));

    // Step 3: 同步 body 高度，让文档可滚动区域 = 视觉高度（消除底部空白）
    function syncHeight() {
        var b = document.body;
        if (!b) return;
        b.style.removeProperty('margin-bottom');
        var h = b.offsetHeight;
        var r = parseFloat(de.getAttribute('data-scale-ratio')) || ratio;
        if (r >= 1) return;
        b.style.setProperty('margin-bottom',
            (-Math.ceil(h * (1 - r))) + 'px', 'important');
    }

    function reapply() {
        var newRatio = computeRatio();
        ratio = newRatio;
        styleEl.textContent = buildCss(newRatio);
        de.setAttribute('data-scale-ratio', String(newRatio));
        syncHeight();
    }

    function startObserver() {
        if (!document.body) return;
        syncHeight();
        if (window.ResizeObserver) {
            try {
                new ResizeObserver(function () { syncHeight(); }).observe(document.body);
            } catch (e) {
                setInterval(syncHeight, 500);
            }
        } else {
            setInterval(syncHeight, 500);
        }
    }

    if (document.body) {
        startObserver();
    } else {
        document.addEventListener('DOMContentLoaded', startObserver);
    }

    window.addEventListener('load', syncHeight);
    window.addEventListener('orientationchange', function () {
        setTimeout(reapply, 80);
    });
    window.addEventListener('resize', function () {
        setTimeout(reapply, 50);
    });
    window.addEventListener('pageshow', function (e) {
        if (e && e.persisted) { reapply(); }
    });
})();
