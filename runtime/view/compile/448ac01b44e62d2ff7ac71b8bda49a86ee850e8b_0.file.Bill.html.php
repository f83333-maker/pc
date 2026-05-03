<?php
/* Smarty version 3.1.46, created on 2026-05-01 02:21:04
  from 'D:\phpstudy_pro\WWW\app\View\User\Theme\Cartoon\User\Bill.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f39d90d5ac17_09950852',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '448ac01b44e62d2ff7ac71b8bda49a86ee850e8b' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\User\\Theme\\Cartoon\\User\\Bill.html',
      1 => 1777554956,
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
function content_69f39d90d5ac17_09950852 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Common/Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<div class="layui-container fly-marginTop fly-user-main">
    <?php $_smarty_tpl->_subTemplateRender("file:../Common/Nav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <div class="fly-panel fly-panel-user" pad20>
        <div class="layui-tab layui-tab-brief">
            <div class="content-header">
                <i class="layui-icon">&#xe638;</i> 我的账单
            </div>

            <div class="content-body">
                <table id="bill-table"></table>
            </div>
        </div>
    </div>
</div>
<?php echo ready("/assets/user/controller/user/bill.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Common/Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
