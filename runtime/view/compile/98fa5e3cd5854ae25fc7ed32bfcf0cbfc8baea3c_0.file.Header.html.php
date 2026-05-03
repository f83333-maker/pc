<?php
/* Smarty version 3.1.46, created on 2026-05-01 21:14:34
  from '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/Common/Header.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f4a73ad00337_63049691',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '98fa5e3cd5854ae25fc7ed32bfcf0cbfc8baea3c' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/Common/Header.html',
      1 => 1777641270,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f4a73ad00337_63049691 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['config']->value['shop_name'];?>
</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['config']->value['keywords'];?>
">
    <meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['config']->value['description'];?>
">
    <meta name="color-scheme" content="dark light">
    <link href="<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
" rel="icon">

    <?php echo '<script'; ?>
>
        (function () {
            var storageKey = 'mountfuji.theme.preference';
            var defaultPreference = '<?php echo $_smarty_tpl->tpl_vars['setting']->value['theme_mode'];?>
';
            var storedPreference = null;
            if (!defaultPreference || ['auto', 'light', 'dark'].indexOf(defaultPreference) === -1) {
                defaultPreference = 'auto';
            }
            try {
                storedPreference = window.localStorage.getItem(storageKey);
            } catch (e) {
                storedPreference = null;
            }
            if (['light', 'dark'].indexOf(storedPreference) === -1) {
                storedPreference = null;
            }
            var preference = storedPreference || defaultPreference;
            var media = window.matchMedia ? window.matchMedia('(prefers-color-scheme: light)') : null;
            var resolved = preference === 'light' || preference === 'dark'
                ? preference
                : (media && media.matches ? 'light' : 'dark');
            document.documentElement.setAttribute('data-theme-storage-key', storageKey);
            document.documentElement.setAttribute('data-theme-default-preference', defaultPreference);
            document.documentElement.setAttribute('data-theme-preference', preference);
            document.documentElement.setAttribute('data-theme-user-override', storedPreference ? '1' : '0');
            document.documentElement.setAttribute('data-theme-mode', resolved);
        }());
    <?php echo '</script'; ?>
>

    <?php echo css(array("/assets/common/css/bootstrap.min.css","/assets/common/css/_.css","/assets/user/css/_user.css","/app/View/User/Theme/MountFuji/Assets/Css/Theme.css"),array("/assets/common/css/font.min.css","/assets/common/js/layui/css/layui.css","/assets/common/css/select2.min.css","/assets/common/css/toastr.min.css","/assets/common/js/table/bootstrap-table.css","/assets/common/js/layer/theme/default/layer.css","/assets/common/css/component.css","/assets/user/css/global_2.css","/app/View/User/Theme/MountFuji/Assets/Css/Theme.css"));?>


    <?php echo js("/assets/common/js/ready.js");?>


    <!--start::HOOK-->
    <?php echo hook(\App\Consts\Hook::USER_VIEW_HEADER);?>

    <!--end::HOOK-->
<?php echo js("/assets/common/js/ready.js");?>


    <!--start::HOOK-->
    <?php echo hook(\App\Consts\Hook::USER_VIEW_HEADER);?>

    <!--end::HOOK-->
    

</head>
<body class="mf-body">
<div class="net-loading" style="display:none;">
    <div class="loading-view">
        <div class="loading-image"></div>
    </div>
</div>

<div class="mf-background"></div>

<header class="mf-topbar">
    <div class="mf-topbar-inner">
        <div class="mf-topbar-main">
            <a class="fly-logo mf-brand" href="/user/dashboard/index" data-mf-sidebar-toggle aria-label="打开导航菜单">
                <span class="mf-brand-mark"><img src="<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
" alt="<?php echo $_smarty_tpl->tpl_vars['config']->value['shop_name'];?>
"></span>
                <span class="mf-brand-copy">
                    <strong><?php echo $_smarty_tpl->tpl_vars['config']->value['shop_name'];?>
</strong>
                    <small>User Center</small>
                </span>
            </a>
            <nav class="mf-topbar-nav">
                <a class="mf-topbar-link layui-hide-xs" href="/" target="_blank">
                    <i class="fa-duotone fa-regular fa-bag-shopping"></i>
                    <span>前往商城</span>
                </a>
                <a class="mf-topbar-link layui-hide-xs" href="/user/personal/purchaseRecord">
                    <i class="fa-duotone fa-regular fa-receipt"></i>
                    <span>购买记录</span>
                </a>
            </nav>
        </div>

        <div class="mf-topbar-right">
            <div class="mf-theme-toggle" role="group" aria-label="主题切换">
                <button type="button" class="mf-theme-button" data-theme-toggle="light" aria-label="切换到浅色模式"
                        title="浅色">
                    <i class="fa-duotone fa-regular fa-sun"></i>
                </button>
                <button type="button" class="mf-theme-button" data-theme-toggle="dark" aria-label="切换到深色模式"
                        title="深色">
                    <i class="fa-duotone fa-regular fa-moon"></i>
                </button>
            </div>

            <div class="mf-user-menu">
                <button type="button" class="mf-user-card" data-mf-user-menu-toggle aria-haspopup="menu"
                        aria-expanded="false">
                    <div class="mf-user-avatar">
                        <img src="<?php echo $_smarty_tpl->tpl_vars['user']->value['avatar'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['user']->value['username'];?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
'">
                    </div>
                    <div class="mf-user-summary">
                        <div class="mf-user-line">
                            <strong><?php echo $_smarty_tpl->tpl_vars['user']->value['username'];?>
</strong>
                            <span class="mf-user-rank">
                                <img src="<?php if ($_smarty_tpl->tpl_vars['group']->value['icon']) {
echo $_smarty_tpl->tpl_vars['group']->value['icon'];
} else {
echo $_smarty_tpl->tpl_vars['favicon']->value;
}?>"
                                     alt="<?php if ($_smarty_tpl->tpl_vars['group']->value['name']) {
echo $_smarty_tpl->tpl_vars['group']->value['name'];
} else { ?>会员等级<?php }?>"
                                     onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
'">
                                <em><?php if ($_smarty_tpl->tpl_vars['group']->value['name']) {
echo $_smarty_tpl->tpl_vars['group']->value['name'];
} else { ?>会员中心<?php }?></em>
                            </span>
                        </div>
                    </div>
                    <span class="mf-user-menu-caret" aria-hidden="true">
                        <i class="fa-duotone fa-regular fa-chevron-down"></i>
                    </span>
                </button>

                <div class="mf-user-dropdown" data-mf-user-menu role="menu" aria-hidden="true">
                    <a href="/user/security/personal"
                       class="mf-user-dropdown-item <?php if (in_array($_smarty_tpl->tpl_vars['title']->value,array('个人资料','邮箱设置','手机设置','密码设置'))) {?>is-active<?php }?>"
                       role="menuitem">
                        <i class="fa-duotone fa-regular fa-shield-halved"></i>
                        <span>安全中心</span>
                    </a>
                    <a href="javascript:void(0);" class="mf-user-dropdown-item logout" role="menuitem">
                        <i class="fa-duotone fa-regular fa-right-from-bracket"></i>
                        <span>安全退出</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="mf-app-shell">
    <div id="pjax-container">
<?php }
}
