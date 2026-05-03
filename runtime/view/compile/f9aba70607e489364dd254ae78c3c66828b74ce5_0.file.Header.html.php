<?php
/* Smarty version 3.1.46, created on 2026-05-03 15:28:52
  from '/www/wwwroot/pcccc.cc/app/View/User/Theme/Cartoon/Index/Header.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f6f9344a7090_21562638',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f9aba70607e489364dd254ae78c3c66828b74ce5' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Theme/Cartoon/Index/Header.html',
      1 => 1777793324,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f6f9344a7090_21562638 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['config']->value['keywords'];?>
"/>
    <meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['config']->value['description'];?>
"/>
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon"/>
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['config']->value['shop_name'];?>
</title>
    <?php echo css(array("/assets/common/css/bootstrap.min.css","/assets/common/css/_.css","/assets/user/css/index.css","/assets/common/css/font.min.css","/assets/common/js/layui/css/layui.css","/assets/common/css/select2.min.css","/assets/common/css/component.css","/assets/common/js/table/bootstrap-table.css","/assets/common/js/layer/theme/default/layer.css","/assets/common/css/toastr.min.css"),array("/assets/common/css/bootstrap.min.css","/assets/common/css/_.css","/assets/user/css/index.css","/assets/common/css/font.min.css","/assets/common/js/layui/css/layui.css","/assets/common/css/select2.min.css","/assets/common/css/component.css","/assets/common/js/table/bootstrap-table.css","/assets/common/js/layer/theme/default/layer.css","/assets/common/css/toastr.min.css"));?>

    <?php echo js("/assets/common/js/ready.js");?>

    <?php echo index_var();?>

    <!--start::HOOK-->
    <?php echo hook(\App\Consts\Hook::USER_GLOBAL_VIEW_HEADER);?>

    <?php echo hook(\App\Consts\Hook::USER_VIEW_INDEX_HEADER);?>

    <!--end::HOOK-->
</head>
<body style="margin: 0; padding: 0;">
<nav class="navbar navbar-expand-lg navbar-acg">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
            <img src="/favicon.png" alt="ACG Logo" class="brand-logo me-2" fetchpriority="high" decoding="async" width="32" height="32">
            <span style="color: #000000;"><?php echo $_smarty_tpl->tpl_vars['config']->value['shop_name'];?>
</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['title']->value == "首页") {?>active<?php }?>" href="/">
                        <i class="fa-duotone fa-regular fa-bag-shopping nav-icon"></i>购物
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['title']->value == "订单查询") {?>active<?php }?>" href="/user/index/query">
                        <i class="fa-duotone fa-regular fa-rectangle-list nav-icon"></i>订单查询
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['title']->value == "2FA验证") {?>active<?php }?>" href="/user/index/twofa">
                        <i class="fa-duotone fa-regular fa-shield-keyhole nav-icon"></i>2FA验证
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center btn-acg-primary-wrapper ms-auto">
                <a href="<?php if ($_smarty_tpl->tpl_vars['user']->value) {?>/user/dashboard/index<?php } else { ?>/user/authentication/login<?php }?>" class="btn btn-acg-primary">
                    <i class="fa-duotone fa-regular fa-circle-user me-2"></i>会员中心
                </a>
            </div>
        </div>
    </div>
</nav>
<div id="pjax-container"><?php }
}
