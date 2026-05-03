<?php
/* Smarty version 3.1.46, created on 2026-05-01 02:24:22
  from 'D:\phpstudy_pro\WWW\app\View\User\Theme\Cartoon\Common\SecurityNav.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f39e56a504d2_31707323',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '867ea0297a861c55ff22bc082c25bc0aa679ab61' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\User\\Theme\\Cartoon\\Common\\SecurityNav.html',
      1 => 1777554956,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f39e56a504d2_31707323 (Smarty_Internal_Template $_smarty_tpl) {
?><ul class="layui-tab-title">
    <li <?php if ($_smarty_tpl->tpl_vars['title']->value == "个人资料") {?> class="layui-this" <?php }?>><a href="/user/security/personal"><i class="layui-icon">&#xe66f;</i> 个人资料</a></li>
    <li <?php if ($_smarty_tpl->tpl_vars['title']->value == "密码设置") {?> class="layui-this" <?php }?>><a href="/user/security/password"><i class="layui-icon">&#xe673;</i> 密码设置</a></li>
    <?php if ($_smarty_tpl->tpl_vars['config']->value['registered_type'] == 2) {?>
    <li <?php if ($_smarty_tpl->tpl_vars['title']->value == "邮箱设置") {?> class="layui-this" <?php }?>><a href="/user/security/email"><i class="layui-icon">&#xe618;</i> 邮箱设置</a></li>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['config']->value['registered_type'] == 1) {?>
    <li <?php if ($_smarty_tpl->tpl_vars['title']->value == "手机设置") {?> class="layui-this" <?php }?>><a href="/user/security/phone"><i class="layui-icon">&#xe678;</i> 手机设置</a></li>
    <?php }?>
    <!--start::HOOK-->
    <?php echo hook(\App\Consts\Hook::USER_VIEW_SECURITY_NAV);?>

    <!--end::HOOK-->
</ul><?php }
}
