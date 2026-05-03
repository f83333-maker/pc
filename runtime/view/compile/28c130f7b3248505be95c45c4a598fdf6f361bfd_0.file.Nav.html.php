<?php
/* Smarty version 3.1.46, created on 2026-05-01 02:15:56
  from 'D:\phpstudy_pro\WWW\app\View\User\Theme\Cartoon\Common\Nav.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f39c5c57f669_01741128',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '28c130f7b3248505be95c45c4a598fdf6f361bfd' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\User\\Theme\\Cartoon\\Common\\Nav.html',
      1 => 1777554956,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f39c5c57f669_01741128 (Smarty_Internal_Template $_smarty_tpl) {
?><ul class="layui-nav layui-nav-tree layui-inline">
    <li  class="top-border layui-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '个人主页') {?> layui-this <?php }?>">
        <a  href="/user/dashboard/index" class="top-border">
            <i class="layui-icon">&#xe68e;</i>
            我的主页
        </a>
    </li>
    <li class="layui-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '我的店铺') {?> layui-this <?php }?>">
        <a href="/user/business/index">
            <i class="layui-icon">&#xe653;</i>
            我的店铺<?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel']) {?><span class="layui-badge layui-bg-pro"><?php echo $_smarty_tpl->tpl_vars['user']->value['businessLevel']['name'];?>
</span><?php }?>
        </a>
    </li>
    <?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel'] && $_smarty_tpl->tpl_vars['user']->value['businessLevel']['supplier'] == 1) {?>
    <li class="layui-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '商品分类') {?> layui-this <?php }?>">
        <a href="/user/category/index">
            <i class="layui-icon">&#xe66e;</i>
            商品分类
        </a>
    </li>
    <li class="layui-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '我的商品') {?> layui-this <?php }?>">
        <a href="/user/commodity/index">
            <i class="layui-icon">&#xe656;</i>
            我的商品
        </a>
    </li>
    <li class="layui-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '卡密管理') {?> layui-this <?php }?>">
        <a href="/user/card/index">
            <i class="layui-icon">&#xe674;</i>
            卡密管理
        </a>
    </li>
    <li class="layui-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '代卷管理') {?> layui-this <?php }?>">
        <a href="/user/coupon/index">
            <i class="layui-icon">&#xe627;</i>
            代卷管理
        </a>
    </li>
    <li class="layui-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '商品订单') {?> layui-this <?php }?>">
        <a href="/user/order/index">
            <i class="layui-icon">&#xe6fc;</i>
            商品订单
        </a>
    </li>
    <?php }?>
    <li class="layui-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '充值中心') {?> layui-this <?php }?>">
        <a href="/user/recharge/index">
            <i class="layui-icon">&#xe65e;</i>
            充值中心
        </a>
    </li>
    <li class="layui-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '购买记录') {?> layui-this <?php }?>">
        <a href="/user/personal/purchaseRecord">
            <i class="layui-icon">&#xe657;</i>
            购买记录
        </a>
    </li>
    <li class="layui-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '硬币兑现' || $_smarty_tpl->tpl_vars['title']->value == '兑现记录') {?> layui-this <?php }?>">
        <a href="/user/cash/index">
            <i class="layui-icon">&#xe735;</i>
            硬币兑现
        </a>
    </li>
    <li class="layui-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '我的下级') {?> layui-this <?php }?>">
        <a href="/user/agent/member">
            <i class="layui-icon">&#xe612;</i>
            我的下级
        </a>
    </li>
    <li class="layui-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '我的账单') {?> layui-this <?php }?>">
        <a href="/user/bill/index">
            <i class="layui-icon">&#xe63c;</i>
            我的账单
        </a>
    </li>
    <li class="layui-nav-item <?php if (in_array($_smarty_tpl->tpl_vars['title']->value,array('个人资料','邮箱设置','手机设置','密码设置'))) {?> layui-this <?php }?>">
        <a href="/user/security/personal">
            <i class="layui-icon">&#xe672;</i>
            安全中心
        </a>
    </li>
    <!--start::HOOK-->
    <?php echo hook(\App\Consts\Hook::USER_VIEW_MENU);?>

    <!--end::HOOK-->
</ul>


<div class="site-mobile-shade"></div>

<?php }
}
