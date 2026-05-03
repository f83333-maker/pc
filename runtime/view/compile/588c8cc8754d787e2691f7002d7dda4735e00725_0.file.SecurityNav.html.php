<?php
/* Smarty version 3.1.46, created on 2026-05-02 13:51:16
  from '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/Common/SecurityNav.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f590d4793ec3_66467980',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '588c8cc8754d787e2691f7002d7dda4735e00725' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/Common/SecurityNav.html',
      1 => 1776658578,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f590d4793ec3_66467980 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="mf-security-nav">
    <ul class="layui-tab-title">
        <li <?php if ($_smarty_tpl->tpl_vars['title']->value == "个人资料") {?>class="layui-this"<?php }?>>
            <a href="/user/security/personal"><i class="fa-duotone fa-regular fa-id-card"></i> 个人资料</a>
        </li>
        <li <?php if ($_smarty_tpl->tpl_vars['title']->value == "密码设置") {?>class="layui-this"<?php }?>>
            <a href="/user/security/password"><i class="fa-duotone fa-regular fa-key"></i> 密码设置</a>
        </li>
        <li <?php if ($_smarty_tpl->tpl_vars['title']->value == "邮箱设置") {?>class="layui-this"<?php }?>>
            <a href="/user/security/email"><i class="fa-duotone fa-regular fa-envelope"></i> 邮箱设置</a>
        </li>
        <li <?php if ($_smarty_tpl->tpl_vars['title']->value == "手机设置") {?>class="layui-this"<?php }?>>
            <a href="/user/security/phone"><i class="fa-duotone fa-regular fa-mobile-screen-button"></i> 手机设置</a>
        </li>
    </ul>
</div>
<?php }
}
