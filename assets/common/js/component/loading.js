class Loading {
    static _overlay = null;
    static _inlineHost = null; // 当前内联宿主
    static _inlineEl = null;   // 当前内联loader
    static _locked = false;

    /**
     * 显示加载动画
     * @param {Object} opts
     *  - inline: 选择器或元素，将 loader 内联插入到该元素中；不传则使用全屏遮罩
     *  - size: 直径（number|css，默认 50 或使用 CSS 变量）
     *  - color: 颜色（默认 #25b09b）
     *  - border: 圈线粗细（number|css，默认 4）
     *  - overlayAlpha: 遮罩透明度（0~1），仅 overlay 模式有效
     */
    static show(opts = {}) {
        return; // 彻底禁用全局加载图标显示
    }

    /** 隐藏（同时清理 overlay 与 inline） */
    static hide() {
        return; // 彻底禁用
    }

    /* ============ 内部 ============ */
    static _ensureOverlay() {
        if (this._overlay) return;
        const ov = document.createElement('div');
        ov.className = 'chahuo-ring-overlay';
        ov.setAttribute('mask-hidden', 'true');

        const ring = document.createElement('span');
        ring.className = 'chahuo-ring';

        ov.appendChild(ring);
        document.body.appendChild(ov);
        this._overlay = ov;
    }

    static _cleanupInline() {
        if (this._inlineEl && this._inlineEl.parentNode) {
            this._inlineEl.parentNode.removeChild(this._inlineEl);
        }
        this._inlineEl = null;
        this._inlineHost = null;
    }

    static _lockScroll() {
        if (this._locked) return;
        this._locked = true;
        const doc = document.documentElement;
        const sbw = window.innerWidth - doc.clientWidth;
        doc.style.overflow = 'hidden';
        if (sbw > 0) doc.style.paddingRight = sbw + 'px';
    }
    static _unlockScroll() {
        if (!this._locked) return;
        this._locked = false;
        const doc = document.documentElement;
        doc.style.overflow = '';
        doc.style.paddingRight = '';
    }
}