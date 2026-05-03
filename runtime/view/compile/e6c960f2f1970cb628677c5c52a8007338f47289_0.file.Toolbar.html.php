<?php
/* Smarty version 3.1.46, created on 2026-04-30 21:47:45
  from 'D:\phpstudy_pro\WWW\app\View\Admin\Toolbar.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f35d812a0cd5_49963511',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e6c960f2f1970cb628677c5c52a8007338f47289' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\Admin\\Toolbar.html',
      1 => 1777554955,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f35d812a0cd5_49963511 (Smarty_Internal_Template $_smarty_tpl) {
?><!--begin::Toolbar-->
<div class="toolbar" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
             class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <!--begin::Title-->
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>
            <!--end::Title-->
            <!--begin::Separator-->
            <span class="h-20px border-gray-200 border-start mx-4"></span>
            <!--end::Separator-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                <?php if (is_array($_smarty_tpl->tpl_vars['toolbar']->value)) {?>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['toolbar']->value, 'bar');
$_smarty_tpl->tpl_vars['bar']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['bar']->value) {
$_smarty_tpl->tpl_vars['bar']->do_else = false;
?>
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <a href="<?php echo $_smarty_tpl->tpl_vars['bar']->value['url'];?>
" class="<?php if (getLocalRouter() == $_smarty_tpl->tpl_vars['bar']->value['url']) {?> text-dark fw-bolder <?php } else { ?> text-muted <?php }?> text-hover-primary"><?php echo $_smarty_tpl->tpl_vars['bar']->value['name'];?>
</a>
                </li>
                <!--end::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-200 w-5px h-2px"></span>
                </li>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                <?php } else { ?>
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    <a href="javascript:void(0)" class="text-muted text-hover-primary">(✪ω✪)</a>
                </li>
                <!--end::Item-->
                <?php }?>


            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
    </div>
    <!--end::Container-->
</div>
<!--end::Toolbar--><?php }
}
