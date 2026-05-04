!function () {
    function _QueryOrders(keywords) {
        util.post({
            url: "/user/api/index/query",
            data: {
                keywords: keywords,
                page: 1,
                limit: 10
            },
            loader: false,
            done: res => {
                _HideLoading();
                if (res?.data?.total == 0) {
                    _ShowNoResults();
                    return;
                }
                _ShowResults(res?.data?.list ?? []);
            },
            error: () => {
                _HideLoading();
                _ShowNoResults();
            },
            fail: () => {
                _HideLoading();
                _ShowNoResults();
            }
        });
    }

    function _ShowLoading() {
        $('.order-results').hide();
        $('.no-results').hide();
        $('.loading-state').show();
    }

    function _HideLoading() {
        $('.loading-state').hide();
    }

    function _ShowNoResults() {
        $('.order-results').hide();
        $('.no-results').show();
    }

    function _GetStatusText(status) {
        const map = {
            0: '<i class="fa-duotone fa-regular fa-clock"></i> 待付款',
            1: '<i class="fa-duotone fa-regular fa-circle-check"></i> 已付款'
        };
        return map[status] || '未知状态';
    }

    function _GetStatusClass(status) {
        const map = {
            0: 'pending',
            1: 'paid',
            2: 'shipped',
            3: 'waiting-shipment'
        };
        return map[status] || 'pending';
    }

    function _GetShipmentStatusText(status) {
        const map = {
            0: '<span class="ov-shipment-badge ov-shipment-waiting"><i class="fa-duotone fa-regular fa-truck-clock me-1"></i>等待发货</span>',
            1: '<span class="ov-shipment-badge ov-shipment-done"><i class="fa-duotone fa-regular fa-circle-check me-1"></i>已发货</span>'
        };
        return map[status] || map[0];
    }

    function _CreateOrderItem(order) {
        
        let skuHtml = '';
        if (order.race) {
            skuHtml += `<span class="ov-tag ov-tag-success">${order.race}</span>`;
        }
        if (!util.isEmptyOrNotJson(order?.sku)) {
            for (const k in order.sku) {
                skuHtml += `<span class="ov-tag ov-tag-primary">${k}: ${order.sku[k]}</span>`;
            }
        }
        skuHtml += `<span class="ov-tag ov-tag-warning"><i class="fa-duotone fa-regular fa-cube me-1"></i>数量 ${order.card_num}</span>`;

        let cardSection = '';
        if (order.status == 1) {
            let inner = '';
            if (order.password === true) {
                inner = `
                    <div class="ov-card-pass-wrap card-content-${order.trade_no}">
                        <div class="ov-pass-input-row">
                            <input type="password"
                                   class="ov-pass-input passin-${order.trade_no}"
                                   placeholder="请输入查询密码">
                            <button type="button"
                                    class="ov-btn-pass view-card-btn"
                                    data-no="${order.trade_no}">
                                <i class="fa-duotone fa-regular fa-eye me-1"></i>查看卡密
                            </button>
                        </div>
                        <div class="ov-pass-tip">
                            <i class="fa-duotone fa-regular fa-shield-keyhole me-1"></i>
                            该订单设置了查询密码，请输入正确密码后查看
                        </div>
                    </div>
                    <div class="ov-card-loading loading-${order.trade_no}" style="display:none;">
                        <i class="fa-duotone fa-regular fa-spinner-third icon-spin"></i>
                        <span>正在解密数据...</span>
                    </div>`;
            } else {
                inner = `
                    <div class="ov-card-content-no-password">
                        <div class="card-display">${order.secret || ''}</div>
                    </div>
                    ${order?.commodity?.leave_message
                        ? `<div class="ov-leave-message"><i class="fa-duotone fa-regular fa-message-lines me-1"></i>${order.commodity.leave_message}</div>`
                        : ""}`;
            }

            cardSection = `
                <div class="ov-card-section">
                    <div class="ov-card-head">
                        <div class="ov-card-title">
                            <i class="fa-duotone fa-regular fa-gift"></i>
                            <span>发货内容</span>
                        </div>
                        <div class="ov-card-shipment">
                            ${_GetShipmentStatusText(order.delivery_status)}
                        </div>
                    </div>
                    <div class="ov-card-body">${inner}</div>
                </div>`;
        }

        const headerHtml = `
            <div class="ov-order-head">
                <div class="ov-order-head-left">
                    <span class="ov-status-badge ov-status-${_GetStatusClass(order.status)}">
                        ${_GetStatusText(order.status)}
                    </span>
                    <span class="ov-order-no">#<span class="order-no-text">${order.trade_no}</span></span>
                </div>
                <div class="ov-order-head-right">
                    <span class="ov-amount-label">订单金额</span>
                    <span class="ov-amount-value">¥<span class="amount-number">${order.amount}</span></span>
                </div>
            </div>`;

        const goodsHtml = `
            <div class="ov-goods-row">
                <div class="ov-goods-thumb">
                    <img src="${order?.commodity?.cover || ''}" alt="商品图片" onerror="this.style.display='none'">
                </div>
                <div class="ov-goods-info">
                    <div class="ov-goods-name">${order?.commodity?.name || '-'}</div>
                    <div class="ov-goods-tags">${skuHtml}</div>
                </div>
            </div>`;

        const metaHtml = `
            <div class="ov-meta-grid">
                <div class="ov-meta-item">
                    <div class="ov-meta-label"><i class="fa-duotone fa-regular fa-calendar-plus"></i> 下单时间</div>
                    <div class="ov-meta-value order-time-text">${order.create_time || '-'}</div>
                </div>
                <div class="ov-meta-item">
                    <div class="ov-meta-label"><i class="fa-duotone fa-regular fa-calendar-check"></i> 付款时间</div>
                    <div class="ov-meta-value payment-time-text">${order.pay_time || '-'}</div>
                </div>
                <div class="ov-meta-item">
                    <div class="ov-meta-label"><i class="fa-duotone fa-regular fa-credit-card"></i> 支付方式</div>
                    <div class="ov-meta-value ov-pay-method">
                        ${order?.pay?.icon ? `<img src="${order.pay.icon}" alt="" class="ov-pay-icon">` : ''}
                        <span>${order?.pay?.name || '-'}</span>
                    </div>
                </div>
            </div>`;

        return `<div class="ov-order-item">
            ${headerHtml}
            ${goodsHtml}
            ${metaHtml}
            ${cardSection}
        </div>`;
    }

    function _ShowResults(orders) {
        $('.order-results').show();
        $('.no-results').hide();

        const orderList = $('.order-list');
        orderList.empty();

        orders.forEach(function (order) {
            orderList.append(_CreateOrderItem(order));
        });
    }

    function _ShowPasswordLoading(tradeNo) {
        $(`.loading-${tradeNo}`).show();
    }

    function _ShowPasswordInput(tradeNo) {
        $(`.card-content-${tradeNo}`).show();
    }

    function _HidePasswordInput(tradeNo) {
        $(`.card-content-${tradeNo}`).hide();
    }

    function _HidePasswordLoading(tradeNo) {
        $(`.loading-${tradeNo}`).hide();
    }

    function _ShowCardContent(tradeNo, content, leaveMessage = null) {
        $(`.card-content-${tradeNo}`).html(`
            <div class="ov-card-content-no-password">
                <div class="card-display">${content}</div>
            </div>
            ${leaveMessage ? `<div class="ov-leave-message"><i class="fa-duotone fa-regular fa-message-lines me-1"></i>${leaveMessage}</div>` : ""}
        `).show();
    }

    $(document).off('click', '.view-card-btn').on('click', '.view-card-btn', function () {
        const tradeNo = $(this).data("no");
        const pass = $(`.passin-${tradeNo}`).val().trim();

        if (!pass) {
            message.error("请输入密码");
            return;
        }

        _ShowPasswordLoading(tradeNo);
        _HidePasswordInput(tradeNo);

        util.post({
            url: "/user/api/index/secret",
            data: {
                tradeNo: tradeNo,
                password: pass
            },
            loader: false,
            done: res => {
                _HidePasswordLoading(tradeNo);
                _ShowCardContent(tradeNo, res?.data?.secret, res?.data?.leave_message);
            },
            error: res => {
                message.error(res.msg ?? "未知错误");
                _HidePasswordLoading(tradeNo);
                _ShowPasswordInput(tradeNo);
            },
            fail: () => {
                message.error("网络错误");
                _HidePasswordLoading(tradeNo);
                _ShowPasswordInput(tradeNo);
            }
        });
    });

    $('.order-query-form').on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData($('.order-query-form')[0]);
        const data = Object.fromEntries(formData.entries());
        const keywords = data?.keywords?.trim();

        if (!keywords) {
            message.error("请输入联系方式或订单号再查询");
            return;
        }

        _ShowLoading();
        _QueryOrders(keywords);
    });

    if (/^\d{18}$/.test(util.getParam("tradeNo"))) {
        $('.btn-search-query').click();
    }
}();
