<?php
/* Smarty version 3.1.46, created on 2026-05-01 18:40:22
  from '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/User/Bill.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f483160b59f4_17531405',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '58fb433279e677de61731b3e488f1369a10adf47' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/User/Bill.html',
      1 => 1776730344,
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
function content_69f483160b59f4_17531405 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Common/Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<div class="mf-page-shell" data-mf-controller="/assets/user/controller/user/bill.js" data-mf-table="#bill-table">
    <?php $_smarty_tpl->_subTemplateRender("file:../Common/Nav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <main class="mf-main">
        <section class="mf-panel">
            <div class="mf-panel-heading">
                <span><i class="fa-duotone fa-regular fa-scroll"></i> 账单明细</span>
            </div>
            <div class="mf-table-wrap">
                <table id="bill-table"></table>
            </div>
        </section>
    </main>
</div>
<?php echo ready("/assets/user/controller/user/bill.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Common/Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
