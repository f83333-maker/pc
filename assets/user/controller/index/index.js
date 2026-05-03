!function () {
    const $ItemList = $(`.item-list`),
          $topCategoryList = $('.chip-list.top-category-list'), // 引用顶层分类列表
          $subCategoryContainer = $topCategoryList.find('.sub-category-container'), // 引用全局的子分类容器
          categoryId = getVar("CAT_ID");

    let ALL_COMMODITIES = []; // 全局商品缓存
    let currentOpenSubCategoryParentId = null; // 跟踪当前展开子分类的父ID

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

    // 渲染子分类到全局的 sub-category-container
function _RenderSubCategories(parentId, activeId = null) {
    $subCategoryContainer.html("").css('display', 'none'); // 清空并隐藏

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
        // 以 flex 方式显示，配合 CSS 中 flex-basis:100% 占满整行
        $subCategoryContainer.css('display', 'flex');
    }
}

    // 切换分类的核心逻辑
    function _SwitchCategory(id, isUserClick = false) {
        currentCategoryId = id;
        
        // 移除所有 switch-category 的 is-primary 状态
        $(`.switch-category`).removeClass("is-primary");
        // 标记当前点击的分类为选中状态
        $(`a[data-id=${id}]`).addClass("is-primary");

        const clickedCategoryData = CATEGORY_TREE.find(c => c.id === id);
        let parentCategoryData = null; 

        // 判断点击的是一级分类还是二级分类
        if (!clickedCategoryData) { // id not found in top-level, so it must be a sub-category
            parentCategoryData = CATEGORY_TREE.find(c => c.children && c.children.some(child => child.id === id));
            if (parentCategoryData) {
                $(`a[data-id=${parentCategoryData.id}]`).addClass("is-primary"); // 高亮父分类
            }
        } else if (clickedCategoryData.children && clickedCategoryData.children.length > 0) {
            // 如果点击的是带子分类的一级分类，其父分类就是自身
            parentCategoryData = clickedCategoryData;
        }

        // 处理子分类容器的显示/隐藏和定位
        $('.top-category-list > .switch-category.chip').removeClass('is-expanded is-primary-expanded'); // 移除所有一级分类的展开状态和高亮
        $subCategoryContainer.css('display', 'none').html(''); // 隐藏并清空全局子分类容器
        currentOpenSubCategoryParentId = null; // 重置展开状态

        if (parentCategoryData && parentCategoryData.children && parentCategoryData.children.length > 0) {
            const clickedPrimaryChip = $(`a[data-id=${parentCategoryData.id}].switch-category.chip`);

            if (currentOpenSubCategoryParentId === parentCategoryData.id && isUserClick && parentCategoryData.id === id) {
                // 如果再次点击已展开的一级分类（且点击的是一级分类本身），则关闭子分类
                currentOpenSubCategoryParentId = null;
                clickedPrimaryChip.removeClass('is-expanded is-primary-expanded');
            } else {
                // 否则，展开子分类
                _RenderSubCategories(parentCategoryData.id, id); // 渲染到全局 $subCategoryContainer
                clickedPrimaryChip.addClass('is-expanded is-primary-expanded'); // 给被点击的一级分类芯片添加展开类
                currentOpenSubCategoryParentId = parentCategoryData.id;

                // —— 样式定位：把 sub-category-container 作为整行插入到「被点击芯片所在行」的最后一个芯片之后 ——
                // 这样依靠 CSS 的 flex-basis:100% 自动占满一行，把后续一级分类挤到下一行，
                // 二级分类容器就会自然出现在父分类行与下一行之间，距离贴合且不遮挡。
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
            // 如果点击的分类没有子分类，或者点击的是已关闭的二级分类，则关闭所有子分类
            currentOpenSubCategoryParentId = null;
        }

        // 优先从本地缓存过滤，实现秒开
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
            // 兜底逻辑：如果缓存还没好，走网络请求
            trade.getCommodityList({
                categoryId: id,
                loader: false,
                done: data => {
                    ALL_COMMODITIES = data; // 确保缓存
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
        $subCategoryContainer.css('display', 'none').html(''); // 搜索时隐藏子分类
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

    // 初始加载所有商品到缓存
    trade.getCommodityList({
        loader: false,
        done: data => {
            ALL_COMMODITIES = data;
            // 缓存加载完成后，如果是初始进入且没显示内容，重新渲染一次
            if ($ItemList.children().length === 0 || $ItemList.find('.item-footer').length > 0) {
                let initialCategoryId = categoryId > 0 ? categoryId : $('.top-category-list > .switch-category').first().data("id");
                _SwitchCategory(initialCategoryId, false); // 初始加载时不视为用户点击
            }
        }
    });

    // 使用事件委托处理所有 .switch-category 的点击事件
    $(document).on('click', '.top-category-list > .switch-category.chip', function (e) {
        e.preventDefault(); // 阻止默认的链接跳转行为
        const clickedId = $(this).data("id");
        _SwitchCategory(clickedId, true); // isUserClick = true
    });

    // 为子分类容器内的芯片绑定点击事件
    $(document).on('click', '.sub-category-container .chip', function(e) {
        e.preventDefault();
        e.stopPropagation(); // 阻止事件冒泡到父级，防止关闭二级分类
        const clickedId = $(this).data("id");
        _SwitchCategory(clickedId, true); // isUserClick = true
    });


    // 搜索相关事件绑定
    $('.item-search-input').on('keypress', function (e) {
        if (e.which === 13) {
            _Search($(this).val());
        }
    }).on('input', function() {
        // 当用户输入时，同步所有搜索框的内容
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

    // 全局点击事件，用于关闭子分类容器
    $(document).on('click', function(e) {
        // 如果点击的不是一级分类芯片，也不是子分类容器本身或其内部
        const $target = $(e.target);
        if (!$target.closest('.top-category-list > .switch-category.chip').length &&
            !$target.closest('.sub-category-container').length) {
            
            $('.top-category-list > .switch-category.chip').removeClass('is-expanded is-primary-expanded');
            $subCategoryContainer.css('display', 'none').html('');
            currentOpenSubCategoryParentId = null;
        }
    });


}();