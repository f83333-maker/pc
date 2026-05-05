/**
 * scale-layout.js
 *
 * 移动端等比缩放适配器（方案 A · 显式 initial-scale 版）
 * ----------------------------------------------------------------
 * 目标：在移动设备上让站点呈现“桌面版页面的等比例缩小”效果，
 *      所有不同尺寸的移动端看到的布局完全一致，仅分辨率缩放，
 *      不发生折行 / 堆叠 / 汉堡菜单切换。
 *
 * 实现：动态改写 <meta name="viewport"> 的 content：
 *        width=<BASE_WIDTH>, initial-scale=<物理宽/BASE>, ...
 *      其中 initial/min/max-scale 显式锁定为同一个比例，强制所有
 *      移动浏览器（含部分 Android Chrome / 华为 / UC / 微信内置等
 *      默认 initial-scale=1 的实现）按物理宽度等比缩放整个 BASE_WIDTH
 *      宽的桌面布局，杜绝“右侧大片空白”或“横向溢出”问题。
 *
 * 范围：仅在判定为“移动设备”时介入；桌面端完全不动，保留原响应式。
 *
 * 配置：可通过在本脚本加载前设置 window.__SCALE_DESIGN_WIDTH__
 *      （数字，默认 1280）来调整设计基准宽度；
 *      设置 window.__DISABLE_SCALE_LAYOUT__ = true 可整体关闭。
 *
 * 注意：本脚本必须在 <head> 中同步加载，且置于 <meta name="viewport"> 之后，
 *      以保证在浏览器首次绘制前完成 viewport 改写。
 */
(function () {
    'use strict';

    if (window.__DISABLE_SCALE_LAYOUT__ === true) {
        return;
    }

    // 设计基准宽度。1280 是兼顾“桌面布局完整不折叠（>= 800）”
    // 与“缩放后字号不至于太小”的折中值。可由外部覆盖。
    var BASE_WIDTH = (typeof window.__SCALE_DESIGN_WIDTH__ === 'number'
        && window.__SCALE_DESIGN_WIDTH__ > 0)
        ? window.__SCALE_DESIGN_WIDTH__
        : 1280;

    // 物理屏幕短边小于该阈值视为移动端
    var MOBILE_THRESHOLD = 1024;

    /**
     * 判定当前是否为移动设备。
     * 同时满足“粗糙指针 + 触屏 + 物理屏幕较窄”三项才视为移动端，
     * 避免把“桌面浏览器拖小窗口”误判成移动端。
     */
    function isMobileDevice() {
        try {
            var coarsePointer = window.matchMedia
                && window.matchMedia('(pointer: coarse)').matches;
            var hasTouch = ('ontouchstart' in window)
                || (navigator.maxTouchPoints || 0) > 0;
            var sw = (window.screen && window.screen.width) || window.innerWidth || 0;
            var sh = (window.screen && window.screen.height) || window.innerHeight || 0;
            var shortSide = Math.min(sw, sh) || sw;
            return !!coarsePointer && !!hasTouch && shortSide < MOBILE_THRESHOLD;
        } catch (e) {
            return false;
        }
    }

    /**
     * 取当前设备“CSS 物理宽度”。
     * 优先使用 window.screen.width 配合方向判断，结果在所有移动浏览器
     * 都比较稳定；当 viewport 已被改写后 innerWidth/clientWidth 会变成
     * BASE_WIDTH，不能再用作物理宽度来源。
     */
    function getPhysicalWidth() {
        var sw = (window.screen && window.screen.width) || 0;
        var sh = (window.screen && window.screen.height) || 0;
        var landscape = !!(window.matchMedia
            && window.matchMedia('(orientation: landscape)').matches);
        var w;
        if (sw && sh) {
            w = landscape ? Math.max(sw, sh) : Math.min(sw, sh);
        } else {
            // 兜底：viewport 还未被改写时 innerWidth 等于物理 CSS 宽
            w = window.innerWidth || document.documentElement.clientWidth || sw || 0;
        }
        return w || BASE_WIDTH;
    }

    /**
     * 计算并应用移动端 viewport 改写。
     */
    function applyMobileViewport() {
        if (!isMobileDevice()) {
            return;
        }
        var physical = getPhysicalWidth();
        // 比例保留 4 位小数，避免不同浏览器对极小数尾数处理不一致
        var scale = Math.round((physical / BASE_WIDTH) * 10000) / 10000;
        if (!isFinite(scale) || scale <= 0) {
            return;
        }
        var content = 'width=' + BASE_WIDTH
            + ', initial-scale=' + scale
            + ', minimum-scale=' + scale
            + ', maximum-scale=' + scale
            + ', user-scalable=no, viewport-fit=cover';

        var meta = document.querySelector('meta[name="viewport"]');
        if (!meta) {
            meta = document.createElement('meta');
            meta.setAttribute('name', 'viewport');
            (document.head || document.documentElement).appendChild(meta);
        }
        if (meta.getAttribute('content') !== content) {
            meta.setAttribute('content', content);
        }
        if (document.documentElement) {
            document.documentElement.setAttribute('data-scale-layout', 'mobile');
            document.documentElement.setAttribute('data-scale-base', String(BASE_WIDTH));
            document.documentElement.setAttribute('data-scale-ratio', String(scale));
        }
    }

    // 立即同步执行一次（必须早于首次绘制）
    applyMobileViewport();

    if (window.addEventListener) {
        // 旋转屏幕后物理短边/长边互换，需要重算 scale
        window.addEventListener('orientationchange', function () {
            // 留 100ms 等浏览器更新 screen.width/height
            setTimeout(applyMobileViewport, 100);
        }, false);
        // BFCache 返回时重新应用
        window.addEventListener('pageshow', function (e) {
            if (e && e.persisted) {
                applyMobileViewport();
            }
        }, false);
    }
})();
