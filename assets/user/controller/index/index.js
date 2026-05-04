!function () {
    const $ItemList = $(`.item-list`),
          $topCategoryList = $('.chip-list.top-category-list'), 
          $subCategoryContainer = $topCategoryList.find('.sub-category-container'), 
          categoryId = getVar("CAT_ID");

    let ALL_COMMODITIES = []; 
    let currentOpenSubCategoryParentId = null; 

    function _PushCommodityList(data) {
        if (data.length === 0) {
            $ItemList.html(`<div class="item-footer">没有找到相关商品</div>`);
            return;
        }

        let html = `
            <div class="item-table-container">
                <div class="table-header">
                    <div class="col-name">商品名称</div>
                    <div class="col-way">发货方式</div>
                    <div class="col-price">单价</div>
                    <div class="col-stock">库存</div>
                    <div class="col-action">操作</div>
                </div>
        `;

        data.forEach(item => {
            const isSoldOut = item.stock === 0;
            let stockStatus = 'high';
            let stockText = '库存充足';

            if (item.stock === 0) {
                stockStatus = 'low';
                stockText = '已售罄';
            } else if (item.stock <= 5) {
                stockStatus = 'low';
                stockText = '库存紧张';
            } else if (item.stock <= 20) {
                stockStatus = 'medium';
                stockText = '库存一般';
            }

            html += `
                <a href="${!isSoldOut ? `/item/${item.id}` : `javascript:void(0);`}" class="table-row" data-id="${item.id}">
                    <div class="col-name">
                        <img src="${item.cover}" class="item-icon" onerror="this.src='/favicon.ico'">
                        <span>${item.name}</span>
                        ${item.recommend === 1 ? `<span class="badge-delivery ms-2" style="color:#ff4d4f;border-color:rgba(255,77,79,0.2);background:rgba(255,77,79,0.1)">推荐</span>` : ``}
                    </div>
                    <div class="col-way">
                        <span class="badge-delivery">${item.delivery_way === 0 ? '自动发货' : '在线发货'}</span>
                    </div>
                    <div class="col-price">¥${item.price}</div>
                    <div class="col-stock">
                        <span class="stock-text ${stockStatus}">${stockText}</span>
                    </div>
                    <div class="col-action">
                        <button class="btn-buy-now">${isSoldOut ? '售罄' : '购买'}</button>
                    </div>
                </a>
            `;
        });

        html += `
            <div class="item-footer">
                共 ${data.length} 个商品 · 数据实时更新
            </div>
        </div>`;

        $ItemList.hide().html(html).fadeIn(200);
    }

function _RenderSubCategories(parentId, activeId = null) {
    $subCategoryContainer.html("").css('display', 'none'); 

    const parent = CATEGORY_TREE.find(c => c.id === parentId);
    if (parent && parent.children && parent.children.length > 0) {
        parent.children.forEach(child => {
            const isActive = child.id === (activeId || currentCategoryId);
            $subCategoryContainer.append(`
                <a data-id="${child.id}" class="switch-category chip ${isActive ? 'is-primary' : ''}" href="javascript:void(0);">
                    ${child.icon ? `<img src="${child.icon}" class="chip-icon" loading="lazy" decoding="async">` : ''}
                    ${child.name}
                </a>
            `);
        });

        $subCategoryContainer.css('display', 'flex');
    }
}

    function _SwitchCategory(id, isUserClick = false) {
        currentCategoryId = id;

        $(`.switch-category`).removeClass("is-primary");

        $(`a[data-id=${id}]`).addClass("is-primary");

        const clickedCategoryData = CATEGORY_TREE.find(c => c.id === id);
        let parentCategoryData = null; 

        if (!clickedCategoryData) { 
            parentCategoryData = CATEGORY_TREE.find(c => c.children && c.children.some(child => child.id === id));
            if (parentCategoryData) {
                $(`a[data-id=${parentCategoryData.id}]`).addClass("is-primary"); 
            }
        } else if (clickedCategoryData.children && clickedCategoryData.children.length > 0) {

            parentCategoryData = clickedCategoryData;
        }

        $('.top-category-list > .switch-category.chip').removeClass('is-expanded is-primary-expanded'); 
        $subCategoryContainer.css('display', 'none').html(''); 
        currentOpenSubCategoryParentId = null; 

        if (parentCategoryData && parentCategoryData.children && parentCategoryData.children.length > 0) {
            const clickedPrimaryChip = $(`a[data-id=${parentCategoryData.id}].switch-category.chip`);

            if (currentOpenSubCategoryParentId === parentCategoryData.id && isUserClick && parentCategoryData.id === id) {

                currentOpenSubCategoryParentId = null;
                clickedPrimaryChip.removeClass('is-expanded is-primary-expanded');
            } else {

                _RenderSubCategories(parentCategoryData.id, id); 
                clickedPrimaryChip.addClass('is-expanded is-primary-expanded'); 
                currentOpenSubCategoryParentId = parentCategoryData.id;

                const $allTopChips = $topCategoryList.children('.switch-category.chip');
                const clickedTop = clickedPrimaryChip.position().top;
                let $lastChipInRow = clickedPrimaryChip;
                $allTopChips.each(function () {
                    if (Math.abs($(this).position().top - clickedTop) < 5) {
                        $lastChipInRow = $(this);
                    }
                });
                $subCategoryContainer.insertAfter($lastChipInRow);
            }
        } else {

            currentOpenSubCategoryParentId = null;
        }

        if (ALL_COMMODITIES.length > 0) {
            let filtered = [];
            if (id === 'recommend') {
                filtered = ALL_COMMODITIES.filter(item => item.recommend === 1);
            } else {
                const categoryToFilter = CATEGORY_TREE.find(c => c.id === id);
                if (categoryToFilter && categoryToFilter.children && categoryToFilter.children.length > 0) {
                    const childIds = categoryToFilter.children.map(c => c.id);
                    filtered = ALL_COMMODITIES.filter(item => item.category_id === id || childIds.includes(item.category_id));
                } else {
                    filtered = ALL_COMMODITIES.filter(item => item.category_id === id);
                }
            }
            _PushCommodityList(filtered);
        } else {

            trade.getCommodityList({
                categoryId: id,
                loader: false,
                done: data => {
                    ALL_COMMODITIES = data; 
                    _PushCommodityList(data);
                }
            });
        }
    }

    function _Search(keywords) {
        if (keywords === '') {
            layer.msg("请输入要搜索的商品名称关键词");
            return;
        }

        $(`.switch-category`).removeClass("is-primary");
        $subCategoryContainer.css('display', 'none').html(''); 
        $('.top-category-list > .switch-category.chip').removeClass('is-expanded is-primary-expanded');
        currentOpenSubCategoryParentId = null;

        if (ALL_COMMODITIES.length > 0) {
            const filtered = ALL_COMMODITIES.filter(item => item.name.toLowerCase().includes(keywords.toLowerCase()));
            _PushCommodityList(filtered);
        } else {
            trade.getCommodityList({
                keywords: keywords,
                loader: false,
                done: data => {
                    _PushCommodityList(data);
                }
            });
        }
    }

    trade.getCommodityList({
        loader: false,
        done: data => {
            ALL_COMMODITIES = data;

            if ($ItemList.children().length === 0 || $ItemList.find('.item-footer').length > 0) {
                let initialCategoryId = categoryId > 0 ? categoryId : $('.top-category-list > .switch-category').first().data("id");
                _SwitchCategory(initialCategoryId, false); 
            }
        }
    });

    $(document).on('click', '.top-category-list > .switch-category.chip', function (e) {
        e.preventDefault(); 
        const clickedId = $(this).data("id");
        _SwitchCategory(clickedId, true); 
    });

    $(document).on('click', '.sub-category-container .chip', function(e) {
        e.preventDefault();
        e.stopPropagation(); 
        const clickedId = $(this).data("id");
        _SwitchCategory(clickedId, true); 
    });

    $('.item-search-input').on('keypress', function (e) {
        if (e.which === 13) {
            _Search($(this).val());
        }
    }).on('input', function() {

        $('.item-search-input').val($(this).val());
    });

    $('.hero-search-btn').on('click', function () {
        const keywords = $(this).closest('.hero-search-wrapper').find('.item-search-input').val();
        _Search(keywords);
    });

    $('.hot-tag').on('click', function () {
        const tag = $(this).text();
        $('.item-search-input').val(tag);
        _Search(tag);
    });

    $(document).on('click', function(e) {

        const $target = $(e.target);
        if (!$target.closest('.top-category-list > .switch-category.chip').length &&
            !$target.closest('.sub-category-container').length) {

            $('.top-category-list > .switch-category.chip').removeClass('is-expanded is-primary-expanded');
            $subCategoryContainer.css('display', 'none').html('');
            currentOpenSubCategoryParentId = null;
        }
    });

}();