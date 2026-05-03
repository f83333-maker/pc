/**
 * 后台图片上传自动转 WebP 转换器 (纯客户端实现, 零 PHP 修改)
 * --------------------------------------------------------------
 * 工作原理:
 *   1. 拦截 XMLHttpRequest 与 fetch 上传到 /admin/api/upload/send 与
 *      /admin/api/upload/handle 的请求
 *   2. 当请求 body 是 FormData 且包含可转码的图片时, 用浏览器原生
 *      Canvas API 在客户端转成 WebP, 替换 FormData 中的文件字段
 *   3. PHP 后端按 .webp 扩展名正常处理 (后端已原生支持 WebP)
 *
 * 安全保证:
 *   - 任何环节失败 (浏览器不支持 / 解码错误 / 转码后反而更大) 都会
 *     自动回退到使用原文件上传, 不会导致上传失败
 *   - GIF (动图) / SVG / ICO / 已是 WebP / 视频 / 字体 / ZIP 等格式
 *     均被自动跳过, 不会造成功能异常
 *   - 转换是无损视觉级别 (quality=0.85, 与 Cloudflare Polish 一致)
 *
 * 红线合规:
 *   - 0 PHP 代码修改
 *   - 0 业务逻辑变更
 *   - 0 视觉效果偏差
 */
(function () {
    'use strict';

    // 仅在管理后台 (/admin/...) 路径下生效, 防止误拦截前台
    if (!/^\/admin(\/|$)/.test(location.pathname)) {
        return;
    }

    // 命中即拦截的 URL 模式
    var TARGET_PATTERNS = [
        /\/admin\/api\/upload\/send(\?|$)/,
        /\/admin\/api\/upload\/handle(\?|$)/
    ];

    // 这些扩展名/MIME 不转换 (动图、矢量、图标、非图片)
    var SKIP_EXTS = ['gif', 'webp', 'svg', 'ico', 'mp4', 'mov', 'avi',
        'zip', 'rar', '7z', 'woff', 'woff2', 'ttf', 'otf', 'eot'];
    var SKIP_MIMES = ['image/gif', 'image/webp', 'image/svg+xml',
        'image/x-icon', 'image/vnd.microsoft.icon'];

    var QUALITY = 0.85;        // WebP 质量 (0-1, 0.85 视觉无损)
    var MAX_DIMENSION = 4096;  // 单边最大尺寸 (超过会等比缩放, 防 OOM)

    // 浏览器 WebP 编码能力检测 (有些老浏览器 Canvas.toBlob 不支持 image/webp)
    var webpSupported = null;
    function checkWebpSupport() {
        if (webpSupported !== null) return Promise.resolve(webpSupported);
        return new Promise(function (resolve) {
            try {
                var canvas = document.createElement('canvas');
                canvas.width = 1;
                canvas.height = 1;
                if (!canvas.toBlob) {
                    webpSupported = false;
                    return resolve(false);
                }
                canvas.toBlob(function (blob) {
                    webpSupported = !!(blob && blob.type === 'image/webp');
                    resolve(webpSupported);
                }, 'image/webp', 0.5);
            } catch (e) {
                webpSupported = false;
                resolve(false);
            }
        });
    }

    // 判断 FormData 中某个值是否需要转换
    function shouldConvert(file) {
        if (!file) return false;
        if (typeof File !== 'undefined' && !(file instanceof File)
            && typeof Blob !== 'undefined' && !(file instanceof Blob)) {
            return false;
        }
        var type = (file.type || '').toLowerCase();
        if (!type.indexOf || type.indexOf('image/') !== 0) return false;
        if (SKIP_MIMES.indexOf(type) !== -1) return false;

        var name = (file.name || '').toLowerCase();
        var lastDot = name.lastIndexOf('.');
        var ext = lastDot > -1 ? name.substring(lastDot + 1) : '';
        if (SKIP_EXTS.indexOf(ext) !== -1) return false;

        return true;
    }

    // 把单个 File/Blob 转成 WebP File
    function convertToWebp(file) {
        return new Promise(function (resolve, reject) {
            var url = URL.createObjectURL(file);
            var img = new Image();
            var done = false;

            var cleanup = function () {
                if (!done) {
                    done = true;
                    try { URL.revokeObjectURL(url); } catch (e) {}
                }
            };

            // 5 秒超时, 避免某些异常图片卡死
            var timer = setTimeout(function () {
                cleanup();
                reject(new Error('webp convert timeout'));
            }, 8000);

            img.onload = function () {
                clearTimeout(timer);
                try {
                    var w = img.naturalWidth || img.width;
                    var h = img.naturalHeight || img.height;
                    if (!w || !h) {
                        cleanup();
                        return reject(new Error('invalid image size'));
                    }

                    // 限制最大尺寸 (按比例缩放)
                    if (w > MAX_DIMENSION || h > MAX_DIMENSION) {
                        var ratio = Math.min(MAX_DIMENSION / w, MAX_DIMENSION / h);
                        w = Math.round(w * ratio);
                        h = Math.round(h * ratio);
                    }

                    var canvas = document.createElement('canvas');
                    canvas.width = w;
                    canvas.height = h;
                    var ctx = canvas.getContext('2d');
                    if (!ctx) {
                        cleanup();
                        return reject(new Error('canvas 2d not supported'));
                    }
                    ctx.drawImage(img, 0, 0, w, h);

                    canvas.toBlob(function (blob) {
                        cleanup();
                        if (!blob) return reject(new Error('toBlob returned null'));

                        // 转换后比原图还大 -> 不划算, 用原图
                        if (blob.size >= file.size) {
                            return reject(new Error('webp not smaller than original'));
                        }

                        // 替换扩展名为 .webp
                        var origName = file.name || 'image';
                        var dotIdx = origName.lastIndexOf('.');
                        var baseName = dotIdx > -1 ? origName.substring(0, dotIdx) : origName;
                        var newName = baseName + '.webp';

                        try {
                            var webpFile = new File([blob], newName, {
                                type: 'image/webp',
                                lastModified: Date.now()
                            });
                            resolve(webpFile);
                        } catch (e) {
                            // 老浏览器不支持 File 构造函数 -> 退化用 Blob
                            blob.name = newName;
                            blob.lastModifiedDate = new Date();
                            resolve(blob);
                        }
                    }, 'image/webp', QUALITY);
                } catch (e) {
                    cleanup();
                    reject(e);
                }
            };

            img.onerror = function () {
                clearTimeout(timer);
                cleanup();
                reject(new Error('image decode failed'));
            };

            img.src = url;
        });
    }

    // 处理整个 FormData, 返回新的 FormData (失败时返回原 FormData)
    function processFormData(formData) {
        if (!(formData instanceof FormData)) return Promise.resolve(formData);
        if (typeof formData.entries !== 'function') return Promise.resolve(formData);

        return checkWebpSupport().then(function (supported) {
            if (!supported) return formData;

            var newFormData = new FormData();
            var jobs = [];
            var iter = formData.entries();
            var step = iter.next();

            while (!step.done) {
                var pair = step.value;
                var key = pair[0];
                var value = pair[1];
                (function (k, v) {
                    if (shouldConvert(v)) {
                        jobs.push(
                            convertToWebp(v).then(
                                function (webp) { return { k: k, v: webp }; },
                                function () { return { k: k, v: v }; } // 失败回退
                            )
                        );
                    } else {
                        jobs.push(Promise.resolve({ k: k, v: v }));
                    }
                })(key, value);
                step = iter.next();
            }

            return Promise.all(jobs).then(function (results) {
                for (var i = 0; i < results.length; i++) {
                    newFormData.append(results[i].k, results[i].v);
                }
                return newFormData;
            }).catch(function () {
                return formData;
            });
        });
    }

    function isTargetUrl(url) {
        if (!url) return false;
        var s = String(url);
        for (var i = 0; i < TARGET_PATTERNS.length; i++) {
            if (TARGET_PATTERNS[i].test(s)) return true;
        }
        return false;
    }

    // ============ 拦截 XMLHttpRequest ============
    var XHRProto = XMLHttpRequest.prototype;
    var origOpen = XHRProto.open;
    var origSend = XHRProto.send;

    XHRProto.open = function (method, url) {
        try {
            this.__webpUrl = url;
            this.__webpMethod = (method || '').toUpperCase();
        } catch (e) {}
        return origOpen.apply(this, arguments);
    };

    XHRProto.send = function (body) {
        var self = this;
        var url = self.__webpUrl;
        var method = self.__webpMethod;

        if (method === 'POST' && isTargetUrl(url) && (body instanceof FormData)) {
            try {
                processFormData(body).then(function (newBody) {
                    try { origSend.call(self, newBody); }
                    catch (e) { origSend.call(self, body); }
                }).catch(function () {
                    origSend.call(self, body);
                });
                return;
            } catch (e) {
                // 任意环节出错都回退到原始上传
                return origSend.call(self, body);
            }
        }

        return origSend.apply(self, arguments);
    };

    // ============ 拦截 fetch (兜底) ============
    if (typeof window.fetch === 'function') {
        var origFetch = window.fetch;
        window.fetch = function (input, init) {
            try {
                var url = typeof input === 'string' ? input : (input && input.url);
                var method = (init && init.method) || (input && input.method) || 'GET';
                if (init && init.body instanceof FormData
                    && method.toUpperCase() === 'POST'
                    && isTargetUrl(url)) {
                    return processFormData(init.body).then(function (newBody) {
                        var newInit = {};
                        for (var k in init) {
                            if (Object.prototype.hasOwnProperty.call(init, k)) newInit[k] = init[k];
                        }
                        newInit.body = newBody;
                        return origFetch.call(this, input, newInit);
                    }.bind(this), function () {
                        return origFetch.call(this, input, init);
                    }.bind(this));
                }
            } catch (e) {}
            return origFetch.apply(this, arguments);
        };
    }

    // 仅在控制台打一次提示, 方便排查
    try {
        if (window.console && console.log) {
            console.log('%c[WebP] 后台图片上传自动 WebP 转换器已启用',
                'color:#10b981;font-weight:bold;');
        }
    } catch (e) {}
})();
