!function () {
    const _item = getVar("_var_item");
    let _price = 0, _available = false;
    const $vstack = $(`.vstack`), $cashPay = $(`.cash-pay`);

    function _hasSkuOptions() {
        return !util.isEmptyOrNotJson(_item?.config?.category)
            || !util.isEmptyOrNotJson(_item?.config?.sku);
    }

    function _getSelectedSku() {
        return $(`.switch-race.is-primary, .switch-sku.is-primary`).first();
    }

    function _isSkuSelected() {
        return _getSelectedSku().length > 0;
    }

    function _getPostData() {
        let post = util.arrayToObject($vstack.serializeArray());
        post["item_id"] = _item.id;

        const $selected = _getSelectedSku();
        if ($selected.length > 0) {
            if ($selected.hasClass("switch-race")) {
                post["race"] = $selected.data("sku");
            } else {
                post["sku"] = {};
                post["sku"][$selected.data("sku")] = $selected.data("value");
            }
        }

        return post;
    }

    function _Abacus(error = null) {

        const $price = $(`.abacus .price`);

        $price.html(`<i class="fa-duotone fa-regular fa-spinner-third icon-spin fs-6"></i>`);

        util.post({
            url: "/user/api/index/valuation",
            data: _getPostData(),
            done: res => {
                $price.html(`<span class="unit">¥</span>${format.amountRemoveTrailingZeros(res.data.price)}`);
                _price = res.data.price;
                _available = true;
            },
            loader: false,
            error: d => {
                typeof error === "function" && error(d);
                $price.html(`<span class="unit">¥</span>${format.amountRemoveTrailingZeros(_price)}`);
            }
        });

        _SetWholesaleMsg();
    }

    function _SnapUp() {
        if (_item.seckill_status != 1) {
            return;
        }

        const $snapUp = $(`.snap-up`);

        const startTime = _item.seckill_start_time;
        const endTime = _item.seckill_end_time;

        util.timer(() => {
            return new Promise(resolve => {
                const now = new Date().getTime();

                if (new Date(startTime).getTime() > now) {

                    const t = format.expireTime(startTime);
                    t && $snapUp.addClass("badge-soft-info").html(`离抢购开始还剩${t}`);
                    resolve(true);
                } else if (new Date(endTime).getTime() > now) {

                    const t = format.expireTime(endTime);
                    t && $snapUp.removeClass("badge-soft-info").addClass("badge-soft-primary").html(`抢购结束还剩${t}`);
                    resolve(true);
                } else {

                    $snapUp.removeClass("badge-soft-success").addClass("badge-soft-muted").html(`抢购已结束`);
                    $cashPay.fadeOut(150);
                    resolve(false);
                }
                $snapUp.show();
            });
        }, 1000, true)
    }

    function _Coupon() {
        $(`.vstack input[name=coupon]`).change(function () {
            _Abacus(d => {
                message.error(d.msg);
                $(this).val("");
            });
        });
    }

    function _SwitchSku() {
        // 独立单选模式：所有 race / sku 选项全局互斥，最多只能选中一个
        $(document).on("click", ".switch-race, .switch-sku", function () {
            const $this = $(this);
            const wasSelected = $this.hasClass("is-primary");
            $(".switch-race, .switch-sku").removeClass("is-primary");
            if (!wasSelected) {
                $this.addClass("is-primary");
            }
            _Abacus();
            _GetStock();
        });
    }

    function _DefaultSelectFirst() {
        // 进入页面默认预选第一个规格（按 DOM 顺序，category 渲染在前则优先选 race，否则选 sku）
        const $first = $(".switch-race, .switch-sku").first();
        if ($first.length > 0) {
            $first.addClass("is-primary");
        }
    }

    function _ChangeNum() {
        const $input = $(`input[name=num]`);

        $input.on('change', function () {
            if (_item.minimum > 0 && $(this).val() < _item.minimum) {
                $(this).val(_item.minimum);
            }
            if (_item.maximum > 0 && $(this).val() > _item.maximum) {
                $(this).val(_item.maximum);
            }

            _Abacus();
        });
    }

    function _SetWholesaleMsg() {
        const $qtyGroup = $(`.qty-group`);
        $(`.wholesale-table`).remove();
        const html = `<table class="table wholesale-table mt-1 mb-0"><thead><tr><th scope="col">批发数量</th><th scope="col">单价</th></tr></thead><tbody>[body]</tbody></table>`;

        // 当前选中的是 race 时优先按 race 显示批发表
        const $selectedRace = $(`.switch-race.is-primary`);
        if ($selectedRace.length > 0 && _item?.config?.category_wholesale) {
            const sku = $selectedRace.data('sku');
            if (_item.config.category_wholesale.hasOwnProperty(sku)) {
                let body = ``;
                for (const k in _item.config.category_wholesale[sku]) {
                    body += `<tr><td>${k}</td><td>¥${_item.config.category_wholesale[sku][k]}</td></tr>`;
                }
                $qtyGroup.after(html.replace("[body]", body));
            }
            return;
        }

        // 选中 sku 选项 或 未选中时回落到通用批发表
        if (!util.isEmptyOrNotJson(_item?.config?.wholesale)) {
            let body = ``;
            for (const k in _item.config.wholesale) {
                body += `<tr><td>${k}</td><td>¥${_item.config.wholesale[k]}</td></tr>`;
            }
            $qtyGroup.after(html.replace("[body]", body));
        }
    }

    function _GetStock() {
        const $itemStock = $(`.item-stock`);
        util.post({
            url: "/user/api/index/stock",
            data: _getPostData(),
            done: res => {
                // 兼容旧 class 名
                $itemStock.removeClass("badge-soft-success badge-soft-danger high medium low");

                if (res.data.stock_state <= 0) {
                    $cashPay.fadeOut(150);
                    $itemStock.addClass("low").html(`已售罄`);
                    return;
                }

                let level = "high";
                if (res.data.stock <= 5) level = "low";
                else if (res.data.stock <= 20) level = "medium";

                $itemStock.addClass(level).html(`库存 ${res.data.stock}`);

                // 商品有 SKU 选项但未选中时不显示支付方式
                if (_hasSkuOptions() && !_isSkuSelected()) {
                    $cashPay.fadeOut(150);
                } else {
                    $cashPay.fadeIn(150);
                }
            },
            loader: false
        });
    }

    function _CaptchaRefresh() {
        const baseSrc = '/user/captcha/image?action=trade';
        $('.captcha-img').attr('src', baseSrc + '&_t=' + Date.now());
    }

    function _RegisterCaptchaRefresh() {
        $('.captcha-img').click(() => {
            _CaptchaRefresh();
        });
    }

    function _SetPayList() {
        const $payList = $(`.pay-list`);
        util.post({
            url: `/user/api/index/pay?itemId=${_item.id}`,
            done: res => {
                $payList.empty();
                res.data.forEach(item => {
                    $payList.append(`<a class="pay" data-id="${item.id}"><img src="${item.icon}"><span>${item.name}</span></a>`);
                });
            },
            loader: false
        });

        $(document).on("click", `.pay-list .pay`, function () {
            if (_hasSkuOptions() && !_isSkuSelected()) {
                message.error("请先选择规格后再付款");
                return;
            }
            let post = _getPostData();
            post["pay_id"] = $(this).data("id");
            util.post("/user/api/order/trade", post, res => {
                if (post["pay_id"] == 1) {

                    treasure.show(res.data.tradeNo, res.data.secret);
                    return;
                }

                window.location.href = res.data.url;
            }, error => {
                message.error(error.msg);
                _CaptchaRefresh();
            });
        });
    }

    function _OptionalCard() {
        const $OptionalCard = $(`.optional-card`);
        let table;

        $OptionalCard.click(() => {
            component.popup({
                submit: (data, index) => {
                    const selections = table.getSelections();
                    if (selections.length == 0) {
                        $(`input[name=card_id]`).val("");
                        $OptionalCard.html(`未自选,将随机发货`);
                    } else {
                        const draftPremium = selections[0].draft_premium > 0 ? selections[0].draft_premium : _item.draft_premium;

                        $OptionalCard.html(`${draftPremium > 0 ? `<span class="text-primary me-1">« ¥${draftPremium} »</span> ` : ''}${selections[0].draft}`);
                        $(`input[name=card_id]`).val(selections[0].id);
                    }

                    layer.close(index);
                    _Abacus();
                },
                tab: [
                    {
                        name: `<i class="fa-duotone fa-regular fa-list-radio"></i> 自助选号`,
                        form: [
                            {
                                name: "sku",
                                type: "custom",
                                complete: (popup, dom) => {
                                    const where = _getPostData();
                                    dom.html(`<div class="mcy-card"><table id="shop-selection-table"></table></div>`);
                                    table = new Table("/user/api/index/card", dom.find('#shop-selection-table'));
                                    table.setPagination(10, [10, 20, 30]);
                                    table.setColumns([
                                        {checkbox: true},
                                        {field: 'draft', title: '剧透内容'},
                                        {
                                            field: 'draft_premium', title: '溢价', formatter: _ => {
                                                if (_ == 0) {
                                                    if (_item.draft_premium > 0) {
                                                        return format.badge("¥" + _item.draft_premium, "a-badge-primary");
                                                    }
                                                    return '-';
                                                }
                                                return format.badge("¥" + _, "a-badge-primary");
                                            }
                                        },
                                    ]);

                                    for (const whereKey in where) {
                                        table.setWhere(whereKey, where[whereKey]);
                                    }

                                    table.setSearch([
                                        {
                                            title: "搜索剧透内容",
                                            name: "search-draft",
                                            type: "input",
                                            width: 300
                                        }
                                    ]);

                                    table.enableSingleSelect();
                                    table.render();
                                }
                            },
                        ]
                    },
                ],
                assign: {},
                autoPosition: true,
                confirmText: `<i class="fa-duotone fa-regular fa-badge-check"></i> 确认选号`,
                width: "620px"
            });
        });
    }

    function _ShareItem() {
        $(`.shared-button`).click(() => {
            util.copyTextToClipboard(_item.share_url, () => {
                layer.msg("分享链接复制成功，快去分享给朋友吧！")
            });
        });
    }

    _SnapUp();
    _SwitchSku();
    _DefaultSelectFirst();
    _ChangeNum();
    _SetWholesaleMsg();
    _Coupon();

    _Abacus();

    _GetStock();

    _SetPayList();
    _RegisterCaptchaRefresh();
    _OptionalCard();

    _ShareItem();
}();
