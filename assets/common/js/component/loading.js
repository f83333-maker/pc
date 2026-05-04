class Loading {
    static _overlay = null;
    static _inlineHost = null; 
    static _inlineEl = null;   
    static _locked = false;

    static show(opts = {}) {
        return; 
    }

    static hide() {
        return; 
    }

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