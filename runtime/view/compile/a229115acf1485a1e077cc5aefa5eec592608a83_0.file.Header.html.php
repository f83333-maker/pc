<?php
/* Smarty version 3.1.46, created on 2026-05-01 13:59:32
  from 'D:\phpstudy_pro\WWW\app\View\User\Theme\Cartoon\Common\Header.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f441449bdff7_71859508',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a229115acf1485a1e077cc5aefa5eec592608a83' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\User\\Theme\\Cartoon\\Common\\Header.html',
      1 => 1777614733,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f441449bdff7_71859508 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['config']->value['title'];?>
</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon"/>

    <?php echo css(array("/assets/common/css/_.css","/assets/user/css/_user.css"),array("/assets/common/css/font.min.css","/assets/common/js/layui/css/layui.css","/assets/common/css/select2.min.css","/assets/common/css/toastr.min.css","/assets/common/js/table/bootstrap-table.css","/assets/common/js/layer/theme/default/layer.css","/assets/common/css/component.css","/assets/user/css/global_2.css"));?>


    <?php echo js("/assets/common/js/ready.js");?>


    <!--start::HOOK-->
    <?php echo hook(\App\Consts\Hook::USER_VIEW_HEADER);?>

    <!--end::HOOK-->
    <style>
        @font-face{font-family:'Oak Sans';src:url('/assets/common/fonts/custom_font/fonts/webfonts/OakSans-Regular.woff2') format('woff2');font-weight:normal;font-style:normal;font-display:swap}@font-face{font-family:'Oak Sans';src:url('/assets/common/fonts/custom_font/fonts/webfonts/OakSans-Bold.woff2') format('woff2');font-weight:bold;font-style:normal;font-display:swap}@font-face{font-family:'Oak Sans';src:url('/assets/common/fonts/custom_font/fonts/webfonts/OakSans-SemiBold.woff2') format('woff2');font-weight:600;font-style:normal;font-display:swap}body,div,span,p,a,li,td,th,input,textarea,select,button{font-family:'Oak Sans',-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif!important}i,svg,.layui-icon,.iconfont,[class*="fa-"],[class*="icon-"],[class^="layui-icon-"],[class*=" layui-icon-"],.fa,.fas,.far,.fab,.fad,.fa-duotone,.fa-regular,.fa-solid,.sortable:after,.sortable:before{display:none!important;content:none!important;visibility:hidden!important;opacity:0!important;width:0!important;height:0!important;margin:0!important;padding:0!important;font-size:0!important;line-height:0!important;border:none!important;background:none!important}*:before,*:after{font-family:'Oak Sans',sans-serif!important}[class*="fa-"]:before,[class*="fa-"]:after,[class*="icon"]:before,[class*="icon"]:after,.layui-icon:before,.layui-icon:after,.iconfont:before,.iconfont:after,.sortable:before,.sortable:after{display:none!important;content:none!important}button,.layui-btn,.btn,.query-button,.layui-badge,.layui-nav-item a,.fly-nav-user li a,.layui-nav-tree .layui-nav-item a,.layui-laypage .layui-laypage-curr .layui-laypage-em{color:#fff!important}.layui-btn *,.btn *,.query-button *,.layui-badge *,.layui-nav-item *,.layui-laypage *{color:#fff!important;fill:#fff!important}.layui-nav-tree .layui-this>a,.layui-nav-tree .layui-this>a *{color:#000!important}.fly-panel,.layui-card,.fly-panel-user{color:#3d49a1}.fly-panel :not(.layui-btn):not(.btn):not(.query-button):not(.layui-badge):not(a),.layui-card :not(.layui-btn):not(.btn):not(.query-button):not(.layui-badge):not(a){color:#3d49a1}.layui-btn-primary{color:#fff!important}:root{--apple-bg:radial-gradient(at 0% 0%,#94d1c4 0%,transparent 70%),radial-gradient(at 100% 0%,#a6d9f0 0%,transparent 70%),radial-gradient(at 50% 50%,#f2bfc5 0%,transparent 80%),radial-gradient(at 0% 100%,#79b3a9 0%,transparent 70%),radial-gradient(at 100% 100%,#bcc5f0 0%,transparent 70%),#f5f7fa;--apple-blue:#3d49a1;--apple-glass-bg:rgba(255,255,255,0.45);--apple-glass-border:rgba(255,255,255,0.3);--acg-glass:saturate(180%) blur(20px);--apple-text:#1d1d1f;--apple-text-muted:#424245}body{background:var(--apple-bg) fixed!important;color:var(--apple-text)!important}.fly-header{background:var(--apple-glass-bg)!important;backdrop-filter:var(--acg-glass)!important;-webkit-backdrop-filter:var(--acg-glass)!important;border-bottom:1px solid var(--apple-glass-border)!important;box-shadow:none!important}.layui-btn,.btn,.btn-primary,.layui-bg-blue,.layui-btn-normal,.layui-btn-danger,.layui-btn-warm,.layui-btn-pro,.btn-active,.active,.layui-btn-pink,.cash-wallet-btn.checked,.query-button,.page-item.active .page-link,.page-list .dropdown-toggle,.layui-nav-tree .layui-this>a,.layui-bg-green,.layui-badge{background:#3d49a1!important;background-color:#3d49a1!important;background-image:none!important;color:#fff!important;border:none!important;box-shadow:none!important;transition:none!important;transform:none!important;opacity:1!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;padding:0 15px!important;height:38px!important;line-height:38px!important;border-radius:8px!important}.layui-btn-sm,.layui-btn-xs{height:30px!important;line-height:30px!important;padding:0 10px!important}.layui-btn *,.btn *,.btn-active *,.active *,.layui-this>a *,.layui-badge *,.query-button *{color:#fff!important}.layui-btn:hover,.btn:hover,.layui-btn:active,.btn:active,.btn-active:hover,.active:hover{background:#3d49a1!important;background-color:#3d49a1!important;opacity:1!important;box-shadow:none!important}.fly-panel,.fly-panel-user,.layui-nav-tree{background:var(--apple-glass-bg)!important;backdrop-filter:var(--acg-glass)!important;-webkit-backdrop-filter:var(--acg-glass)!important;border:1px solid var(--apple-glass-border)!important;border-radius:18px!important;box-shadow:0 8px 32px rgba(0,0,0,0.03)!important;color:var(--apple-text)!important}.fly-user-main,.layui-card,.layui-table,.elem-quote,blockquote,.table-search,.layui-tab,.content-body{background:transparent!important;background-color:transparent!important;box-shadow:none!important;border:none!important}.card-data .layui-card{background:rgba(255,255,255,0.1)!important;border:1px solid rgba(255,255,255,0.1)!important;border-radius:12px!important}.fly-panel :not(.layui-btn):not(.layui-badge):not(.btn):not(.query-button):not(i),.layui-card :not(.layui-btn):not(.layui-badge):not(.btn):not(.query-button):not(i),.layui-tab-title li a,.fly-nav-avatar cite,.layui-breadcrumb a:hover,.content-header,.form-header,.card-data .layui-card-header,.card-data .layui-card-body{color:#3d49a1!important}.fly-logo,.fly-logo *,.fly-nav a,.fly-nav a *{color:#000!important;transition:none!important;animation:none!important;text-decoration:none!important}.fly-logo:hover,.fly-logo:active,.fly-nav a:hover,.fly-nav a:active{color:#000!important;opacity:1!important}.layui-btn-pink,.layui-bg-pro,.layui-bg-cash,.btn-active,.active,.layui-laypage .layui-laypage-curr .layui-laypage-em,.cash-wallet-btn.checked,.query-button{background:#3d49a1!important;background-color:#3d49a1!important;background-image:none!important;color:#fff!important}.elem-quote,blockquote,.layui-tab-title .layui-this:after,.content-header{border-color:#3d49a1!important}.layui-nav-tree{width:200px!important}.fly-user-main>.layui-nav{position:absolute!important;left:0!important;top:0!important}.fly-user-main>.fly-panel{margin-left:215px!important;padding:20px!important;min-height:80vh!important}.layui-nav-tree .layui-nav-item,.layui-nav-tree .layui-nav-item a{background:transparent!important;color:var(--apple-text)!important}.layui-nav-bar,.layui-nav-tree .layui-nav-bar{display:none!important;background-color:transparent!important;opacity:0!important}.layui-nav-tree .layui-nav-item a:hover,.layui-nav-tree .layui-nav-item:hover,.layui-nav-tree .layui-nav-item:hover>a,.layui-nav-tree .layui-nav-child dd a:hover{background:transparent!important;background-image:none!important;color:var(--apple-text)!important;transition:none!important}.layui-nav-tree .layui-this>a,.layui-nav-tree .layui-this>a:hover,.layui-nav-tree .layui-nav-child dd.layui-this a{background:rgba(0,0,0,0.1)!important;backdrop-filter:blur(25px) saturate(200%)!important;-webkit-backdrop-filter:blur(25px) saturate(200%)!important;border:1px solid rgba(255,255,255,0.3)!important;color:#000!important;box-shadow:0 10px 30px rgba(0,0,0,0.1),inset 0 0 1px rgba(255,255,255,0.2)!important;font-weight:800!important;transition:none!important;border-radius:12px!important;margin:5px 10px!important;width:auto!important;line-height:40px!important;height:40px!important;display:flex!important;align-items:center!important;justify-content:center!important}.layui-nav-tree .layui-this>a *{color:#000!important;font-weight:800!important}.layui-nav-tree .layui-nav-item a{margin:5px 10px!important;border-radius:12px!important;height:40px!important;line-height:40px!important;display:flex!important;align-items:center!important;justify-content:center!important;padding:0!important}.bg-content,.fly-header{background-image:none!important}
    </style>
</head>
<body style="background: var(--apple-bg) fixed !important;">
<div class="net-loading" style="display: none;">
    <div class="loading-view">
        <div class="loading-image"></div>
    </div>
</div>
<div style="background: transparent;overflow:auto;height:100vh;" class="bg-content">
    <div class="fly-header">
        <div class="layui-container">
            <a class="fly-logo">
                <span class="user-logo"><img src="<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
"></span>
                <span class="user-logo-title"><?php echo $_smarty_tpl->tpl_vars['config']->value['shop_name'];?>
</span>
            </a>
            <a class="fly-mobile-shop-link" target="_blank" href="/">去购物</a>
            <ul class="layui-nav fly-nav layui-hide-xs">
                <li class="nav-item">
                    <a href="/user/personal/purchaseRecord"><i class="layui-icon">&#xe657;</i>我的订单</a>
                </li>
                <li class="nav-item">
                    <a href="/" target="_blank"><i class="layui-icon">&#xe653;</i>购买商品</a>
                </li>
            </ul>

            <ul class="layui-nav fly-nav-user">
                <!-- 登入后的状态 -->
                <li class="layui-nav-item">
                    <div class="fly-nav-avatar" href="javascript:;">
                        <img src="<?php if ($_smarty_tpl->tpl_vars['user']->value['avatar']) {
echo $_smarty_tpl->tpl_vars['user']->value['avatar'];
} else { ?>/favicon.png<?php }?>" class="layui-nav-img">
                        <cite><?php echo $_smarty_tpl->tpl_vars['user']->value['username'];?>
</cite>
                    </div>
                    <dl class="layui-nav-child">
                        <dd><a href="/user/security/personal" style="text-align: center;"><i class="layui-icon"
                                                                                             style="font-size: 18px;">&#xe66f;</i>个人资料</a>
                        </dd>
                        <dd><a href="/user/security/password" style="text-align: center;"><i class="layui-icon"
                                                                                             style="font-size: 18px;">&#xe673;</i>修改密码</a>
                            <hr style="margin: 5px 0;">
                        <dd><a href="javascript:void(0);" class="logout" style="text-align: center;"><i
                                        class="layui-icon"
                                        style="font-size: 18px;">&#xe682;</i>安全注销</a>
                        </dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>


    <div id="pjax-container">
    <?php echo '<script'; ?>
>
        (function(){const e=()=>{const t=document.querySelectorAll('i, svg, .layui-icon, .iconfont, [class*="fa-"]');t.forEach(t=>{if(t.tagName==='I'||t.tagName==='svg'||t.classList.contains('layui-icon')||t.classList.contains('iconfont')){t.remove()}});const o=document.querySelectorAll('*');o.forEach(e=>{const t=window.getComputedStyle(e,':before');if(t.content&&t.content!=='none'&&t.content!=='""'){e.style.setProperty('--fa','none','important')}})};e();const t=new MutationObserver(e);t.observe(document.body,{childList:!0,subtree:!0});setInterval(e,1e3)})();
    <?php echo '</script'; ?>
>
<?php }
}
