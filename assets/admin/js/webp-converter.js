
(function () {
    'use strict';

    if (!/^\/admin(\/|$)/.test(location.pathname)) {
        return;
    }

    var TARGET_PATTERNS = [
        /\/admin\/api\/upload\/send(\?|$)/,
        /\/admin\/api\/upload\/handle(\?|$)/
    ];

    var SKIP_EXTS = ['gif', 'webp', 'svg', 'ico', 'mp4', 'mov', 'avi',
        'zip', 'rar', '7z', 'woff', 'woff2', 'ttf', 'otf', 'eot'];
    var SKIP_MIMES = ['image/gif', 'image/webp', 'image/svg+xml',
        'image/x-icon', 'image/vnd.microsoft.icon'];

    var QUALITY = 0.85;        
    var MAX_DIMENSION = 4096;  

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

                        if (blob.size >= file.size) {
                            return reject(new Error('webp not smaller than original'));
                        }

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
                                function () { return { k: k, v: v }; } 
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

                return origSend.call(self, body);
            }
        }

        return origSend.apply(self, arguments);
    };

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

    try {
        if (window.console && console.log) {
            console.log('%c[WebP] 后台图片上传自动 WebP 转换器已启用',
                'color:#10b981;font-weight:bold;');
        }
    } catch (e) {}
})();
