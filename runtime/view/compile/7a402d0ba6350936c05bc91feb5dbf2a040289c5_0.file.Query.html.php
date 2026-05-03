<?php
/* Smarty version 3.1.46, created on 2026-05-01 12:49:46
  from 'D:\phpstudy_pro\WWW\app\View\User\Theme\Cartoon\Index\Query.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f430ea59cea4_16743698',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7a402d0ba6350936c05bc91feb5dbf2a040289c5' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\User\\Theme\\Cartoon\\Index\\Query.html',
      1 => 1777610912,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./Header.html' => 1,
    'file:./Footer.html' => 1,
  ),
),false)) {
function content_69f430ea59cea4_16743698 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:./Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<main class="container py-4">
  <!-- 订单查询面板 -->
  <div class="panel">
    <div class="panel-header">
      <span class="icon">🔍</span>
      <h6 class="panel-title">订单查询</h6>
    </div>
    <div class="panel-body">
      <form class="order-query-form">
        <div class="hero-search-wrapper" style="max-width: 600px; margin: 0 auto;">
          <i class="fa-duotone fa-regular fa-magnifying-glass search-icon"></i>
          <input type="text" class="hero-search-input" name="keywords" value="<?php echo $_smarty_tpl->tpl_vars['tradeNo']->value;?>
" placeholder="订单号/联系方式">
          <button type="submit" class="hero-search-btn btn-search-query">
            <i class="fa-duotone fa-regular fa-sparkles"></i> 查询订单
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- 查询结果区域 -->
  <div class="order-results" style="display: none;">
    <div class="panel">
      <div class="panel-header">
        <span class="icon">📋</span>
        <h6 class="panel-title">查询结果</h6>
      </div>
      <div class="panel-body">
        <div class="order-list">

        </div>
      </div>
    </div>
  </div>

  <!-- 无结果提示 -->
  <div class="no-results" style="display: none;">
    <div class="panel pt-3">
      <div class="panel-body text-center">
        <div class="mb-3">
          <i class="fa-duotone fa-regular fa-search" style="font-size: 3rem; color: #6b7280;"></i>
        </div>
        <h6 class="text-muted">未找到相关订单</h6>
        <p class="text-muted mb-0">请检查输入信息是否正确，或联系客服协助查询</p>
      </div>
    </div>
  </div>

  <!-- 加载状态 -->
  <div class="loading-state" style="display: none;">
    <div class="panel">
      <div class="panel-body text-center">
        <div class="mb-3 mt-3">
          <i class="fa-duotone fa-regular fa-spinner-third icon-spin" style="font-size: 2rem; color: var(--acg-primary);"></i>
        </div>
        <p class="text-muted mb-0">正在查询订单信息...</p>
      </div>
    </div>
  </div>
</main>


<?php echo ready("/assets/user/controller/index/query.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:./Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
