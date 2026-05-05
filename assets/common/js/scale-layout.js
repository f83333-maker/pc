/**
 * scale-layout.js
 *
 * 移动端等比缩放适配器（方案 A）
 * ----------------------------------------------------------------
 * 目标：在移动设备上让站点呈现"桌面版页面的等比例缩小"效果，
 *      所有不同尺寸的移动端看到的布局完全一致，仅分辨率缩放。
 *
 * 实现：动态改写 <meta name="viewport"> 的 content，将 width
 *      固定为设计基准宽度（BASE_WIDTH），浏览器会原生地把整个
 *      桌面布局等比缩小到设备物理宽度，不影响 DOM 结构、JS 坐标、
 *      sticky/fixed 定位以及现有交互（layer/layui/pjax 等）。
 *
 * 范围：仅在判定为"移动设备"时介入；桌面端完全不动，保留原响应式。
 *
 * 关闭：在本脚本加载前设置 window.__DISABLE_SCALE_LAYOUT__ = true 即可。
 *
 * 注意：本脚本必须在 <head> 中同步加载，且置于 <meta name="viewport"> 之后，
 *      以保证在浏览器首次绘制前完成 viewport 改写。
 */
(function () {
    'use strict';

    if (window.__DISABLE_SCALE_LAYOUT__ === true) {
        return;
    }

    // 设计基准宽度（与桌面端最大设计稿宽度对齐）
    var BASE_WIDTH = 1920;

    // 物理屏幕宽度小于该阈值视为移动端
    var MOBILE_THRESHOLD = 1024;

    // 改写后的 viewport 内容
    var META_CONTENT = 'width=' + BASE_WIDTH + ', user-scalable=no, viewport-fit=cover';

    /**
     * 判定当前是否为移动设备。
     * 同时满足"粗糙指针 + 触屏 + 物理屏幕较窄"三项才视为移动端，
     * 避免把"桌面浏览器拖小窗口"误判成移动端。
     */
    function isMobileDevice() {
        try {
            var coarsePointer = window.matchMedia
                && window.matchMedia('(pointer: coarse)').matches;
            var hasTouch = ('ontouchstart' in window)
                || (navigator.maxTouchPoints || 0) > 0;
            var screenW = (window.screen && window.screen.width)
                ? window.screen.width
                : window.innerWidth;
            return !!coarsePointer && !!hasTouch && screenW < MOBILE_THRESHOLD;
        } catch (e) {
            return false;
        }
    }

    /**
     * 应用移动端 viewport 改写。
     * 在 head 中查找已有的 meta[name=viewport]，若有则更新 content，
     * 若没有则新建并追加（理论上业务模板都已存在，此处仅兜底）。
     */
    function applyMobileViewport() {
        if (!isMobileDevice()) {
            return;
        }
        var meta = document.querySelector('meta[name="viewport"]');
        if (!meta) {
            meta = document.createElement('meta');
            meta.setAttribute('name', 'viewport');
            (document.head || document.documentElement).appendChild(meta);
        }
        if (meta.getAttribute('content') !== META_CONTENT) {
            meta.setAttribute('content', META_CONTENT);
        }
        // 在 <html> 上加一个标记属性，便于排查与可能的 CSS 兜底
        if (document.documentElement) {
            document.documentElement.setAttribute('data-scale-layout', 'mobile');
        }
    }

    // 立即同步执行一次（必须早于首次绘制）
    applyMobileViewport();

    if (window.addEventListener) {
        // 旋转屏幕后再次确认 viewport 内容
        window.addEventListener('orientationchange', applyMobileViewport, false);
        // BFCache 返回时重新应用
        window.addEventListener('pageshow', function (e) {
            if (e && e.persisted) {
                applyMobileViewport();
            }
        }, false);
    }
})();
