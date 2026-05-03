<?php
/* Smarty version 3.1.46, created on 2026-05-01 17:14:29
  from '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/User/Business.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f46ef5301cb1_75846423',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '67f646b9b648460a7c629329de0eee558c3e410b' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/User/Business.html',
      1 => 1776730358,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../Common/Header.html' => 1,
    'file:../Common/Nav.html' => 1,
    'file:../Common/Footer.html' => 1,
  ),
),false)) {
function content_69f46ef5301cb1_75846423 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Common/Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
echo set_script_var(array("_business_notice_var"=>$_smarty_tpl->tpl_vars['business']->value['notice']));?>

<div class="mf-page-shell mf-page-shell-business">
    <?php $_smarty_tpl->_subTemplateRender("file:../Common/Nav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <main class="mf-main">
        <section class="mf-panel">
            <div class="mf-panel-heading">
                <span><i class="fa-duotone fa-regular fa-store"></i> 店铺配置</span>
            </div>

            <?php if (!$_smarty_tpl->tpl_vars['business']->value || $_smarty_tpl->tpl_vars['purchase']->value == 1 && $_smarty_tpl->tpl_vars['business']->value) {?>
                <form class="layui-form mf-business-purchase-form">
                    <div class="form-block mf-business-purchase-shell">
                        <section class="mf-business-purchase-hero">
                            <div class="mf-business-purchase-copy">
                                <span class="mf-business-purchase-kicker"><?php if (!$_smarty_tpl->tpl_vars['business']->value) {?>Store Opening<?php }
if ($_smarty_tpl->tpl_vars['purchase']->value == 1) {?>Level Upgrade<?php }?></span>
                                <h2 class="mf-business-purchase-title"><?php if (!$_smarty_tpl->tpl_vars['business']->value) {?>开通你的店铺<?php }
if ($_smarty_tpl->tpl_vars['purchase']->value == 1) {?>升级你的店铺<?php }?></h2>
                                <p class="mf-business-purchase-description">
                                    <?php if (!$_smarty_tpl->tpl_vars['business']->value) {?>选择合适的套餐后即可开通分站，快速开始经营自己的店铺。<?php }?>
                                    <?php if ($_smarty_tpl->tpl_vars['purchase']->value == 1) {?>切换到更高等级，解锁独立域名、供货权限和更完整的经营能力。<?php }?>
                                </p>
                                <div class="mf-business-purchase-pills">
                                    <span><i class="fa-duotone fa-regular fa-circle-check"></i> 分站返佣</span>
                                    <span><i class="fa-duotone fa-regular fa-globe"></i> 独立域名</span>
                                    <span><i class="fa-duotone fa-regular fa-box-open-full"></i> 供货权限</span>
                                </div>
                            </div>

                            <?php if ($_smarty_tpl->tpl_vars['purchase']->value == 1 && $_smarty_tpl->tpl_vars['business']->value) {?>
                                <div class="mf-business-purchase-state">
                                    <span class="mf-business-purchase-state-label">当前等级</span>
                                    <div class="mf-business-purchase-state-main">
                                        <span class="mf-business-purchase-state-icon">
                                            <img src="<?php echo $_smarty_tpl->tpl_vars['user']->value['businessLevel']['icon'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['user']->value['businessLevel']['name'];?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
'">
                                        </span>
                                        <div>
                                            <strong><?php echo $_smarty_tpl->tpl_vars['user']->value['businessLevel']['name'];?>
</strong>
                                            <p>终身可用</p>
                                        </div>
                                    </div>
                                </div>
                            <?php }?>
                        </section>

                        <div class="mf-business-purchase-section">
                            <div class="form-header mf-business-purchase-heading">
                                <div>
                                    <span class="mf-business-purchase-heading-title">选择套餐</span>
                                    <p class="mf-business-purchase-heading-text">选中一个套餐后即可<?php if (!$_smarty_tpl->tpl_vars['business']->value) {?>开通店铺<?php }
if ($_smarty_tpl->tpl_vars['purchase']->value == 1) {?>升级店铺<?php }?>。</p>
                                </div>
                            </div>
                            <div class="form-body">
                                <div class="mf-choice-grid mf-business-plan-grid">
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['level']->value, 'le');
$_smarty_tpl->tpl_vars['le']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['le']->value) {
$_smarty_tpl->tpl_vars['le']->do_else = false;
?>
                                        <a class="business-group mf-business-plan" data-id="<?php echo $_smarty_tpl->tpl_vars['le']->value['id'];?>
" href="javascript:void(0);">
                                            <span class="mf-business-plan-badge">
                                                <i class="fa-duotone fa-regular fa-store"></i>
                                                店铺套餐
                                            </span>
                                            <div class="mf-level-top">
                                                <span class="mf-level-icon-shell">
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['le']->value['icon'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['le']->value['name'];?>
">
                                                </span>
                                                <div class="mf-level-main">
                                                    <strong><?php echo $_smarty_tpl->tpl_vars['le']->value['name'];?>
</strong>
                                                    <div class="mf-muted">终身可用</div>
                                                </div>
                                                <span class="mf-level-price">
                                                    <small>价格</small>
                                                    <b>¥<?php echo $_smarty_tpl->tpl_vars['le']->value['price'];?>
</b>
                                                </span>
                                            </div>
                                            <hr>
                                            <ul class="mf-feature-list">
                                                <li><span>分站功能</span><b class="<?php if ($_smarty_tpl->tpl_vars['le']->value['substation'] == 1) {?>text-success<?php } else { ?>text-danger<?php }?>"><?php if ($_smarty_tpl->tpl_vars['le']->value['substation'] == 1) {?>支持<?php } else { ?>不支持<?php }?></b></li>
                                                <li><span>分站返佣</span><b class="text-success">支持</b></li>
                                                <li><span>独立域名</span><b class="<?php if ($_smarty_tpl->tpl_vars['le']->value['top_domain'] == 1) {?>text-success<?php } else { ?>text-danger<?php }?>"><?php if ($_smarty_tpl->tpl_vars['le']->value['top_domain'] == 1) {?>支持<?php } else { ?>不支持<?php }?></b></li>
                                                <li><span>供货权限</span><b class="<?php if ($_smarty_tpl->tpl_vars['le']->value['supplier'] == 1) {?>text-success<?php } else { ?>text-danger<?php }?>"><?php if ($_smarty_tpl->tpl_vars['le']->value['supplier'] == 1) {?>支持<?php } else { ?>不支持<?php }?></b></li>
                                                <li><span>供货手续费</span><b class="<?php if ($_smarty_tpl->tpl_vars['le']->value['supplier'] == 1) {?>mf-quote-accent<?php } else { ?>mf-muted<?php }?>"><?php if ($_smarty_tpl->tpl_vars['le']->value['supplier'] == 1) {
echo $_smarty_tpl->tpl_vars['le']->value['cost']*100;?>
%<?php } else { ?>-<?php }?></b></li>
                                            </ul>
                                        </a>
                                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                </div>
                            </div>
                        </div>

                        <div class="elem-tips mf-business-purchase-note">
                            <div class="mf-business-purchase-note-head">
                                <i class="fa-duotone fa-regular fa-circle-info"></i>
                                <span>权益说明</span>
                            </div>
                            <ul class="mf-note-list mf-business-note-list">
                                <li>分站返佣：售出主站商品后，按成交价与拿货价的差额返佣。</li>
                                <li>独立域名：开通后可绑定自己的顶级域名，不再局限系统分配子域名。</li>
                                <li>供货权限：支持自建分类、上架商品，并由主站协助售卖。</li>
                                <li>供货手续费：对自主上架商品的成功订单按比例收取手续费。</li>
                            </ul>
                        </div>

                        <div class="mf-page-actions mf-business-purchase-actions">
                            <button type="button" class="layui-btn layui-btn-pink payButton mf-business-submit-button">
                                <i class="fa-duotone fa-regular fa-bolt"></i>
                                <?php if (!$_smarty_tpl->tpl_vars['business']->value) {?>立即开通店铺<?php }
if ($_smarty_tpl->tpl_vars['purchase']->value == 1) {?>立即升级店铺<?php }?>
                            </button>
                            <?php if ($_smarty_tpl->tpl_vars['purchase']->value == 1) {?>
                                <a href="/user/business/index" class="layui-btn mf-business-secondary-button">
                                    <i class="fa-duotone fa-regular fa-arrow-left"></i>
                                    返回店铺
                                </a>
                            <?php }?>
                        </div>
                    </div>
                </form>
            <?php } else { ?>
                <?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel']['substation'] == 1) {?>
                    <blockquote class="elem-quote mf-business-note">
                        <div class="mf-business-note-item is-level">
                            <span class="mf-business-note-label">
                                <i class="fa-duotone fa-regular fa-store"></i>
                                商户等级 <?php if (count($_smarty_tpl->tpl_vars['level']->value) > 0) {?>
                                    <a href="/user/business/index?purchase=1" class="mf-business-note-link">
                                    <i class="fa-duotone fa-regular fa-arrow-up-right-from-square"></i>
                                    我要升级
                                </a>
                                <?php }?>
                            </span>
                            <strong class="mf-business-note-value"><?php echo $_smarty_tpl->tpl_vars['user']->value['businessLevel']['name'];?>
</strong>
                        </div>

                        <div class="mf-business-note-item">
                            <span class="mf-business-note-label">
                                <i class="fa-duotone fa-regular fa-globe"></i>
                                独立域名
                            </span>
                            <strong class="mf-business-note-value <?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel']['top_domain'] == 1) {?>text-success<?php } else { ?>text-danger<?php }?>">
                                <?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel']['top_domain'] == 1) {?>支持<?php } else { ?>不支持<?php }?>
                            </strong>
                        </div>

                        <div class="mf-business-note-item">
                            <span class="mf-business-note-label">
                                <i class="fa-duotone fa-regular fa-box-open-full"></i>
                                供货权限
                            </span>
                            <strong class="mf-business-note-value <?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel']['supplier'] == 1) {?>text-success<?php } else { ?>text-danger<?php }?>">
                                <?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel']['supplier'] == 1) {?>支持<?php } else { ?>不支持<?php }?>
                            </strong>
                        </div>

                        <div class="mf-business-note-item">
                            <span class="mf-business-note-label">
                                <i class="fa-duotone fa-regular fa-percent"></i>
                                供货手续费
                            </span>
                            <strong class="mf-business-note-value <?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel']['supplier'] == 1) {?>mf-quote-accent<?php } else { ?>mf-muted<?php }?>">
                                <?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel']['supplier'] == 1) {
echo $_smarty_tpl->tpl_vars['user']->value['businessLevel']['cost']*100;?>
%<?php } else { ?>-<?php }?>
                            </strong>
                        </div>
                    </blockquote>

                    <form class="layui-form form-data">
                        <div class="mf-business-tabs" data-mf-business-tabs>
                            <button type="button" class="mf-business-tab-trigger is-active" data-mf-business-tab-trigger="basic">
                                <i class="fa-duotone fa-regular fa-sliders"></i>
                                基本设置
                            </button>
                            <button type="button" class="mf-business-tab-trigger" data-mf-business-tab-trigger="product">
                                <i class="fa-duotone fa-regular fa-box-open-full"></i>
                                商品设置
                            </button>
                        </div>

                        <section class="mf-business-tab-panel mf-page-shell-security is-active" data-mf-business-tab-panel="basic">
                            <div class="form-block">
                                <div>
                                    <div class="form-header">店铺名称</div>
                                    <div class="form-body">
                                        <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['business']->value['shop_name'];?>
" name="shop_name" required autocomplete="off" placeholder="请输入店铺名称" class="layui-input">
                                    </div>
                                </div>

                                <div>
                                    <div class="form-header">网站标题</div>
                                    <div class="form-body">
                                        <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['business']->value['title'];?>
" name="title" required autocomplete="off" placeholder="用于显示浏览器标题" class="layui-input">
                                    </div>
                                </div>



                                <div>
                                    <div class="form-header">
                                        子域名
                                        <?php if ($_smarty_tpl->tpl_vars['business']->value['subdomain']) {?>
                                            <span class="mf-business-domain-status">
                                                <i class="fa-duotone fa-regular fa-circle-check"></i>
                                                已绑定
                                            </span>
                                            <a href="javascript:void(0);" class="unbind-subdomain mf-business-domain-action">
                                                <i class="fa-duotone fa-regular fa-link-slash"></i>
                                                解绑
                                            </a>
                                        <?php }?>
                                    </div>
                                    <div class="form-body">
                                        <div class="mf-domain-row">
                                            <input type="text"
                                                   value="<?php echo $_smarty_tpl->tpl_vars['business']->value['subdomain'];?>
"
                                                   name="subdomain"
                                                   required
                                                   autocomplete="off"
                                                   placeholder="输入子域名前缀"
                                                   class="layui-input"
                                                   <?php if ($_smarty_tpl->tpl_vars['business']->value['subdomain']) {?>disabled<?php }?>>
                                            <select name="suffix" <?php if ($_smarty_tpl->tpl_vars['business']->value['subdomain']) {?>disabled<?php }?>>
                                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['domain']->value, 'd');
$_smarty_tpl->tpl_vars['d']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['d']->value) {
$_smarty_tpl->tpl_vars['d']->do_else = false;
?>
                                                    <option value="<?php echo $_smarty_tpl->tpl_vars['d']->value;?>
">.<?php echo $_smarty_tpl->tpl_vars['d']->value;?>
</option>
                                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel']['top_domain'] == 1) {?>
                                    <div>
                                        <div class="form-header">
                                            独立域名
                                            <?php if ($_smarty_tpl->tpl_vars['business']->value['topdomain']) {?>
                                                <a href="javascript:void(0);" class="unbind-topdomain">解绑</a>
                                                [<?php if (gethostbyname($_smarty_tpl->tpl_vars['config']->value['cname']) == gethostbyname($_smarty_tpl->tpl_vars['business']->value['topdomain'])) {?>
                                                <b class="text-success">解析正常</b>
                                            <?php } else { ?>
                                                <b class="text-danger">未检测到解析</b>
                                            <?php }?>]
                                            <?php }?>
                                            <span class="mf-muted">CNAME：<?php echo $_smarty_tpl->tpl_vars['config']->value['cname'];?>
</span>
                                        </div>
                                        <div class="form-body">
                                            <input type="text"
                                                   value="<?php echo $_smarty_tpl->tpl_vars['business']->value['topdomain'];?>
"
                                                   name="topdomain"
                                                   required
                                                   autocomplete="off"
                                                   placeholder="绑定独立域名"
                                                   class="layui-input"
                                                   <?php if ($_smarty_tpl->tpl_vars['business']->value['topdomain']) {?>disabled<?php }?>>
                                        </div>
                                    </div>
                                <?php }?>

                                <div>
                                    <div class="form-header">显示主站商品 [全局]</div>
                                    <div class="form-body">
                                        <input type="checkbox" name="master_display" title="开启" value="1" <?php if ($_smarty_tpl->tpl_vars['business']->value['master_display'] == 1) {?>checked<?php }?>>
                                    </div>
                                </div>

                                <div>
                                    <div class="form-header">店铺公告</div>
                                    <div class="form-body">
                                        <div class="editor-wrapper">
                                            <div>
                                                <button data-type="0" class="button-switch-notice" type="button">
                                                    <i class="fa-duotone fa-regular fa-code me-1"></i>HTML
                                                </button>
                                            </div>
                                            <div class="editor-content">
                                                <div class="toolbar-container"></div>
                                                <div class="editor-container"></div>
                                            </div>
                                            <textarea class="text-container" style="display:none;" name="notice"></textarea>
                                        </div>
                                    </div>
                                </div>


                                <div class="mf-page-actions mf-primary-action-bar mt-4">
                                    <button type="button" class="layui-btn layui-btn-pink save-config mf-primary-action-button">
                                        <i class="fa-duotone fa-regular fa-floppy-disk"></i> 保存设置
                                    </button>
                                </div>
                            </div>
                        </section>

                        <section class="mf-business-tab-panel" data-mf-business-tab-panel="product">
                            <div class="form-block">
                                <div>
                                    <div class="form-header">
                                        <span class="a-badge a-badge-success category-show mf-business-visibility-toggle" data-state="1" style="cursor:pointer;">
                                            <i class="fa-duotone fa-regular fa-eye"></i>
                                            <span>全部显示</span>
                                        </span>
                                        <span class="a-badge a-badge-danger category-show mf-business-visibility-toggle" data-state="0" style="cursor:pointer;">
                                            <i class="fa-duotone fa-regular fa-eye-slash"></i>
                                            <span>全部隐藏</span>
                                        </span>
                                    </div>
                                    <div class="form-body mf-table-wrap">
                                        <table id="master_category" lay-filter="master_category"></table>
                                    </div>
                                </div>

                                <div data-mf-business-commodity-anchor></div>
                                <div data-mf-business-commodity-section>
                                    <div class="form-header">
                                        <a id="commodity"></a>
                                        <span class="a-badge a-badge-success commodity-show mf-business-visibility-toggle" data-state="1" style="cursor:pointer;">
                                            <i class="fa-duotone fa-regular fa-eye"></i>
                                            <span>全部显示</span>
                                        </span>
                                        <span class="a-badge a-badge-danger commodity-show mf-business-visibility-toggle" data-state="0" style="cursor:pointer;">
                                            <i class="fa-duotone fa-regular fa-eye-slash"></i>
                                            <span>全部隐藏</span>
                                        </span>
                                        <span class="a-badge a-badge-primary commodity-premium" style="cursor:pointer;">批量加价</span>
                                    </div>
                                    <div class="form-body mf-table-wrap">
                                        <table id="master_commodity" lay-filter="master_commodity"></table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </form>
                <?php } else { ?>
                    <blockquote class="elem-quote">
                        您当前商户等级暂不支持店铺功能，<a href="/user/business/index?purchase=1" class="text-link">立即升级</a>
                    </blockquote>
                <?php }?>
            <?php }?>
        </section>
    </main>
</div>
<?php echo ready("/assets/user/controller/business/business.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Common/Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
