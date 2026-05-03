<?php
/* Smarty version 3.1.46, created on 2026-05-01 02:15:20
  from 'D:\phpstudy_pro\WWW\app\View\User\Theme\Cartoon\Authentication\Header.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f39c38131071_48065850',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5f4507db6a2b6c6b673cc0d61ce728de34c34c17' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\User\\Theme\\Cartoon\\Authentication\\Header.html',
      1 => 1777572901,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f39c38131071_48065850 (Smarty_Internal_Template $_smarty_tpl) {
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['config']->value['keywords'];?>
"/>
    <meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['config']->value['description'];?>
"/>
    <link href="<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
?v=<?php echo $_smarty_tpl->tpl_vars['app']->value['version'];?>
" rel="icon">
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['config']->value['shop_name'];?>
</title>
    <?php echo css(array("/assets/common/css/bootstrap.min.css","/assets/common/css/_.css","/assets/user/css/_auth.css"),array("/assets/common/css/font.min.css","/assets/common/js/layui/css/layui.css","/assets/common/css/select2.min.css","/assets/common/css/component.css","/assets/common/js/table/bootstrap-table.css","/assets/common/js/layer/theme/default/layer.css","/assets/common/css/bootstrap.min.css","/assets/common/css/toastr.min.css","/assets/user/css/auth.css"));?>

    <?php echo js("/assets/common/js/ready.js");?>


    <!--start::HOOK-->
    <?php echo hook(\App\Consts\Hook::USER_GLOBAL_VIEW_HEADER);?>

    <!--end::HOOK-->
    <style>
        @font-face {
            font-family: 'Oak Sans';
            src: url('/assets/common/fonts/custom_font/fonts/webfonts/OakSans-Regular.woff2') format('woff2');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Oak Sans';
            src: url('/assets/common/fonts/custom_font/fonts/webfonts/OakSans-Bold.woff2') format('woff2');
            font-weight: bold;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Oak Sans';
            src: url('/assets/common/fonts/custom_font/fonts/webfonts/OakSans-SemiBold.woff2') format('woff2');
            font-weight: 600;
            font-style: normal;
            font-display: swap;
        }

        /* 强制全局应用 Oak Sans 字体 */
        *, html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video, input, textarea, select, button {
            font-family: 'Oak Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
        }

        :root {
            --apple-bg: radial-gradient(at 0% 0%, #94d1c4 0%, transparent 70%), 
                        radial-gradient(at 100% 0%, #a6d9f0 0%, transparent 70%), 
                        radial-gradient(at 50% 50%, #f2bfc5 0%, transparent 80%),
                        radial-gradient(at 0% 100%, #79b3a9 0%, transparent 70%), 
                        radial-gradient(at 100% 100%, #bcc5f0 0%, transparent 70%), 
                        #f5f7fa;
            --apple-text: #1d1d1f;
            --apple-text-muted: #424245;
            --apple-blue: #3d49a1;
            --apple-blue-hover: #2e398a;
            --apple-glass-bg: rgba(255, 255, 255, 0.45);
            --apple-glass-border: rgba(255, 255, 255, 0.3);
            --apple-card-shadow: 0 8px 32px rgba(0, 0, 0, 0.03);
            --apple-radius: 18px;
            --acg-glass: saturate(180%) blur(20px);
            --acg-neon-green: #28a745;
        }
    </style>
</head>

<body style="background: var(--apple-bg) fixed !important;"><?php }
}
