<?php
/* Smarty version 3.1.46, created on 2026-05-01 01:03:24
  from 'D:\phpstudy_pro\WWW\app\View\User\Theme\Cartoon\Index\Index.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f38b5c94aa13_27490965',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bd0f6e50cc10f6c0f7d269940f9d8cd51590e32c' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\User\\Theme\\Cartoon\\Index\\Index.html',
      1 => 1777568586,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./Header.html' => 1,
    'file:./Footer.html' => 1,
  ),
),false)) {
function content_69f38b5c94aa13_27490965 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:./Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<div class="hero-apple">
    <div class="container">
        <!-- 全新版本徽章 -->
        <div class="hero-badge">
            <span class="badge-dot"></span>
            <span class="badge-text">NEW</span>
            <span class="badge-divider">|</span>
            <span class="badge-desc">全新 2.0 版本已上线</span>
            <i class="fa-duotone fa-regular fa-chevron-right badge-arrow"></i>
        </div>

        <h1 class="hero-title"><?php echo $_smarty_tpl->tpl_vars['config']->value['shop_name'];?>
</h1>
        <p class="hero-subtitle">一站式跨境资源采购平台 助力全球化业务拓展</p>
        
        <!-- 核心优势 -->
        <div class="hero-features">
            <div class="feature-item">
                <i class="fa-duotone fa-regular fa-shield-check"></i>
                <span>安全可靠</span>
            </div>
            <div class="feature-item">
                <i class="fa-duotone fa-regular fa-bolt"></i>
                <span>即时交付</span>
            </div>
            <div class="feature-item">
                <i class="fa-duotone fa-regular fa-globe"></i>
                <span>全球服务</span>
            </div>
        </div>

        <div class="hero-search">
            <div class="hero-search-wrapper">
                <i class="fa-duotone fa-regular fa-magnifying-glass search-icon"></i>
                <input type="text" class="hero-search-input item-search-input" placeholder="搜索您需要的商品...">
                <button class="hero-search-btn">
                    <i class="fa-duotone fa-regular fa-sparkles"></i> 搜索
                </button>
            </div>
            <div class="hero-hot-tags">
                <span class="hot-label">热门:</span>
                <a href="javascript:void(0);" class="hot-tag">社交媒体</a>
                <a href="javascript:void(0);" class="hot-tag">海外邮箱</a>
                <a href="javascript:void(0);" class="hot-tag">营销工具</a>
                <a href="javascript:void(0);" class="hot-tag">出海必备</a>
            </div>
        </div>
    </div>
</div>
<main class="container py-4">
  <!-- 公告面板 -->
  <div class="panel">
    <div class="panel-header">
      <span class="icon"><i class="fa-duotone fa-regular fa-bullhorn"></i></span>
      <h6 class="panel-title">公告</h6>
    </div>
    <div class="panel-body">
        <?php echo $_smarty_tpl->tpl_vars['config']->value['notice'];?>

    </div>
  </div>

  <div class="mb-4">
    <div class="chip-list top-category-list">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['category']->value, 'cate');
$_smarty_tpl->tpl_vars['cate']->index = -1;
$_smarty_tpl->tpl_vars['cate']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['cate']->value) {
$_smarty_tpl->tpl_vars['cate']->do_else = false;
$_smarty_tpl->tpl_vars['cate']->index++;
$_smarty_tpl->tpl_vars['cate']->first = !$_smarty_tpl->tpl_vars['cate']->index;
$__foreach_cate_0_saved = $_smarty_tpl->tpl_vars['cate'];
?>
            <a data-id="<?php echo $_smarty_tpl->tpl_vars['cate']->value['id'];?>
" class="switch-category chip <?php if (($_smarty_tpl->tpl_vars['cate']->first && $_smarty_tpl->tpl_vars['categoryId']->value == 0) || $_smarty_tpl->tpl_vars['cate']->value['id'] == $_smarty_tpl->tpl_vars['categoryId']->value) {?> is-primary <?php }?>" href="javascript:void(0);">
                <?php if ($_smarty_tpl->tpl_vars['cate']->value['id'] != 'recommend' && $_smarty_tpl->tpl_vars['cate']->value['icon']) {?>
                    <img src="<?php echo $_smarty_tpl->tpl_vars['cate']->value['icon'];?>
" class="chip-icon">
                <?php }?>
                <?php echo $_smarty_tpl->tpl_vars['cate']->value['name'];?>

            </a>
        <?php
$_smarty_tpl->tpl_vars['cate'] = $__foreach_cate_0_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </div>
    <div class="chip-list sub-category-list mt-2" style="display: none; border-top: 1px solid rgba(0,0,0,0.05); padding-top: 10px;">
        <!-- 子分类由 JS 动态渲染 -->
    </div>
  </div>

  <?php echo '<script'; ?>
>
    const CATEGORY_TREE = <?php echo json_encode($_smarty_tpl->tpl_vars['category']->value);?>
;
  <?php echo '</script'; ?>
>

  <div class="item-list">
      <!-- 商品由 JS 动态渲染 -->
  </div>
</main>
<?php echo ready("/assets/user/controller/index/index.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:./Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
