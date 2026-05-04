window._data_var = {};

function documentReady(callback) {
    if (document.readyState === "complete" || document.readyState === "interactive") {
        callback();
    } else {
        document.addEventListener("DOMContentLoaded", callback, false);
    }
}

// 等到 layui 实际可用（layui 是其它 script 异步加载进来的）
function _waitForLayui(maxWaitMs) {
    return new Promise((resolve) => {
        const start = Date.now();
        if (typeof window.layui !== "undefined" && typeof window.layui.use === "function") {
            return resolve();
        }
        const timer = setInterval(() => {
            if (typeof window.layui !== "undefined" && typeof window.layui.use === "function") {
                clearInterval(timer);
                resolve();
            } else if (Date.now() - start > (maxWaitMs || 8000)) {
                clearInterval(timer);
                // 超时也 resolve，让上层逻辑可以兜底，不阻塞用户交互
                resolve();
            }
        }, 50);
    });
}

function ready(call) {
    documentReady(() => {
        // layui 现在通过 defer 加载，DOMContentLoaded 触发时可能还未就绪，
        // 所以必须等 layui 真正可用，再注入业务脚本/绑定事件。
        // 这是交互关键路径，绝不能用 requestIdleCallback 延后，否则用户点击会丢失。
        _waitForLayui(8000).then(() => {
            const exec = () => {
                if (typeof call === "function") {
                    call();
                    return;
                }
                if (!call) return;

                document.querySelectorAll('script[ready]').forEach(s => s.remove());

                const s = document.createElement('script');
                s.src = call;
                s.async = true;
                s.setAttribute('ready', 'true');
                document.body.appendChild(s);

                if (typeof util !== "undefined" && util && typeof util.debug === "function") {
                    util.debug(`RELOAD -> ${call}`, "#10d18f");
                }
            };

            if (typeof window.layui !== "undefined" && typeof window.layui.use === "function") {
                window.layui.use('form', exec);
            } else {
                // layui 8 秒内仍不可用时直接执行，至少保证业务脚本能跑、点击能响应
                exec();
            }
        });
    });
}

function setVar(name, data) {
    window._data_var[name] = data;
}

function getVar(name) {
    return window._data_var[name];
}

function i18n(text) {
    return text;
}

function evalResults(code) {
    return eval('(' + code + ')');
}

function route(uri) {
    uri = uri.replace(/^\/+|\/+$/g, '');
    const pathname = location.pathname;
    const rt = pathname.trim().split("/").filter(Boolean);
    if (rt[0] !== "plugin") {
        return "";
    }

    if (rt[1] === undefined) {
        return "";
    }

    if (!/^\d+$/.test(rt[1])) {

        return `/plugin/${rt[1]}/${uri}`;
    } else {

        if (rt[2] === undefined) {
            return "";
        }
        return `/plugin/${rt[1]}/${rt[2]}/${uri}`;
    }
}
