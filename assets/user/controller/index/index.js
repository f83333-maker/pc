!function () {
    const $ItemList = $(`.item-list`),
          $topCategoryList = $('.chip-list.top-category-list'), 
          $subCategoryContainer = $topCategoryList.find('.sub-category-container[data-level="2"]'), 
          $subSubCategoryContainer = $topCategoryList.find('.sub-category-container[data-level="3"]'), 
          categoryId = getVar("CAT_ID");

    let ALL_COMMODITIES = []; 
    let currentOpenSubCategoryParentId = null; 

    // === 三级分类支持：递归 helper ===
    function _FindCategoryPath(id) {
        function dfs(nodes, ancestors) {
            for (const node of nodes) {
                if (node.id === id) return ancestors.concat([node]);
                if (node.children && node.children.length > 0) {
                    const found = dfs(node.children, ancestors.concat([node]));
                    if (found) return found;
                }
            }
            return null;
        }
        return dfs(CATEGORY_TREE, []) || [];
    }

    function _CollectDescendantIds(node) {
        const ids = [node.id];
        if (node.children && node.children.length > 0) {
            node.children.forEach(c => {
                ids.push.apply(ids, _CollectDescendantIds(c));
            });
        }
        return ids;
    }

    function _RenderSubSubCategories(parentId, activeId = null) {
        $subSubCategoryContainer.html('').css('display', 'none');
        const path = _FindCategoryPath(parentId);
        const parent = path.length > 0 ? path[path.length - 1] : null;
        if (parent && parent.children && parent.children.length > 0) {
            parent.children.forEach(child => {
                const isActive = child.id === activeId;
                $subSubCategoryContainer.append(`
                    <a data-id="${child.id}" class="switch-category chip ${isActive ? 'is-primary' : ''}" href="javascript:void(0);">
                        ${child.icon ? `<img src="${child.icon}" class="chip-icon" loading="lazy" decoding="async">` : ''}
                        ${child.name}
                    </a>
                `);
            });
            $subSubCategoryContainer.css('display', 'flex');
        }
    }


    function _PushCommodityList(data, onComplete) {
        if (data.length === 0) {
            $ItemList.html(`<div class="item-footer">没有找到相关商品</div>`);
            if (typeof onComplete === 'function') onComplete();
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

            const deliveryText = item.delivery_way === 0 ? '自动发货' : '在线发货';
            html += `
                <a href="${!isSoldOut ? `/item/${item.id}` : `javascript:void(0);`}" class="table-row" data-id="${item.id}">
                    <div class="col-name">
                        <img src="${item.cover}" class="item-icon" onerror="this.src='/favicon.ico'">
                        <div class="item-name-block">
                            <div class="item-name-row">
                                <span class="item-name-text">${item.name}</span>
                                ${item.recommend === 1 ? `<span class="badge-recommend">推荐</span>` : ``}
                            </div>
                            <div class="item-tags-mobile">
                                <span class="tag-pill tag-delivery">${deliveryText}</span>
                                <span class="tag-pill tag-stock ${stockStatus}">${stockText}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-way">
                        <span class="badge-delivery">${deliveryText}</span>
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

        $ItemList.hide().html(html).fadeIn(200, function () {
            if (typeof onComplete === 'function') onComplete();
        });
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

        // 递归找路径（支持任意层级）
        const path = _FindCategoryPath(id);
        const level1 = path[0] || null; // 顶级
        const level2 = path[1] || null; // 二级
        const level3 = path[2] || null; // 三级

        // 高亮：当前点击 + 顶级祖先（保持原有"顶级一直高亮"的视觉）
        $(`a[data-id="${id}"]`).addClass("is-primary");
        if (level1) {
            $(`.top-category-list > a[data-id="${level1.id}"]`).addClass("is-primary");
        }

        // 重置展开 / 容器
        $('.top-category-list > .switch-category.chip').removeClass('is-expanded is-primary-expanded');
        $subCategoryContainer.css('display', 'none').html('');
        $subSubCategoryContainer.css('display', 'none').html('');
        currentOpenSubCategoryParentId = null;

        // 渲染二级（沿用原有 insertAfter 行末尾的展开逻辑）
        if (level1 && level1.children && level1.children.length > 0) {
            const clickedPrimaryChip = $(`.top-category-list > a[data-id="${level1.id}"].switch-category.chip`);
            _RenderSubCategories(level1.id, level2 ? level2.id : null);
            clickedPrimaryChip.addClass('is-expanded is-primary-expanded');
            currentOpenSubCategoryParentId = level1.id;

            const $allTopChips = $topCategoryList.children('.switch-category.chip');
            const clickedTop = clickedPrimaryChip.position().top;
            let $lastChipInRow = clickedPrimaryChip;
            $allTopChips.each(function () {
                if (Math.abs($(this).position().top - clickedTop) < 5) {
                    $lastChipInRow = $(this);
                }
            });
            $subCategoryContainer.insertAfter($lastChipInRow);

            // 渲染三级（如果当前路径上的二级分类还有 children）
            if (level2 && level2.children && level2.children.length > 0) {
                _RenderSubSubCategories(level2.id, level3 ? level3.id : null);
                $subSubCategoryContainer.insertAfter($subCategoryContainer);
            }
        }

        // 切换后滚到页面绝对顶部 helper：作为 _PushCommodityList 的 fadeIn 完成回调。
        // 用 window.scrollTo(0, 0) 瞬时滚动，不依赖任何元素位置，
        // 不受 sub-container insertAfter / fadeIn / sticky header 的 reflow 影响，
        // 一级 / 二级 / 三级 chip 切换行为完全一致。
        const scrollAfterRender = () => {
            if (!isUserClick) return;
            window.scrollTo(0, 0);
        };

        // 商品过滤：递归收集所有后代分类 id
        if (ALL_COMMODITIES.length > 0) {
            let filtered = [];
            if (id === 'recommend') {
                filtered = ALL_COMMODITIES.filter(item => item.recommend === 1);
            } else {
                const currentNode = path.length > 0 ? path[path.length - 1] : null;
                if (currentNode && currentNode.children && currentNode.children.length > 0) {
                    const ids = _CollectDescendantIds(currentNode);
                    filtered = ALL_COMMODITIES.filter(item => ids.includes(item.category_id));
                } else {
                    filtered = ALL_COMMODITIES.filter(item => item.category_id === id);
                }
            }
            _PushCommodityList(filtered, scrollAfterRender);
        } else {
            trade.getCommodityList({
                categoryId: id,
                loader: false,
                done: data => {
                    ALL_COMMODITIES = data;
                    _PushCommodityList(data, scrollAfterRender);
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
        $subSubCategoryContainer.css('display', 'none').html('');
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
            $subSubCategoryContainer.css('display', 'none').html('');
            currentOpenSubCategoryParentId = null;
        }
    });

}();
