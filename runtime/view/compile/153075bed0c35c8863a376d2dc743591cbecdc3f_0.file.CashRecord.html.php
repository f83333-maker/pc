<?php
/* Smarty version 3.1.46, created on 2026-05-01 02:35:42
  from 'D:\phpstudy_pro\WWW\app\View\User\Theme\Cartoon\User\CashRecord.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f3a0fe71d3d2_31725502',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '153075bed0c35c8863a376d2dc743591cbecdc3f' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\User\\Theme\\Cartoon\\User\\CashRecord.html',
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
function content_69f3a0fe71d3d2_31725502 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Common/Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<div class="layui-container fly-marginTop fly-user-main">
    <?php $_smarty_tpl->_subTemplateRender("file:../Common/Nav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <div class="fly-panel fly-panel-user" pad20>
        <div class="layui-tab layui-tab-brief" lay-filter="user">
            <ul class="layui-tab-title">
                <li><a href="/user/cash/index"><i class="layui-icon">&#xe659;</i> 硬币兑现</a></li>
                <li class="layui-this"><a href="/user/cash/record"><i class="layui-icon">&#xe63c;</i> 兑现记录</a></li>
            </ul>
            <div class="content-body">
                <table id="cash-table"></table>
            </div>
        </div>
    </div>
</div>

<?php echo ready("/assets/user/controller/user/cash.record.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Common/Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
