<?php
/* Smarty version 3.1.46, created on 2026-04-30 21:48:02
  from 'D:\phpstudy_pro\WWW\app\View\Admin\Config\Setting.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f35d929366e3_32539827',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a9e8bab271d1b04b095b6b2f40f9ba62ce2830cf' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\Admin\\Config\\Setting.html',
      1 => 1777554955,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../Header.html' => 1,
    'file:../Toolbar.html' => 1,
    'file:../Footer.html' => 1,
  ),
),false)) {
function content_69f35d929366e3_32539827 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
echo set_script_var(array("_config"=>$_smarty_tpl->tpl_vars['config']->value,"_themes"=>$_smarty_tpl->tpl_vars['themes']->value));?>

<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <?php $_smarty_tpl->_subTemplateRender("file:../Toolbar.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Basic info-->
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header border-0 cursor-pointer">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">基本设置</h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--begin::Card header-->
                <!--begin::Content-->
                <div id="kt_account_profile_details" class="collapse show">
                    <!--begin::Form-->
                    <form id="data-form" class="form">
                        <!--begin::Card body-->
                        <div class="card-body  p-9">
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">LOGO</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <!--begin::Image input-->
                                    <div class="image-input image-input-outline" data-kt-image-input="true"
                                         style="background-image: url(/assets/admin/images/setting/blank.png)">
                                        <!--begin::Preview existing logo-->
                                        <div class="image-input-wrapper w-125px h-125px"
                                             style="background-image: url(/favicon.ico)"></div>
                                        <!--end::Preview existing logo-->
                                        <!--begin::Label-->
                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                               data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                               title="Change logo">
                                            <i class="fa-duotone fa-regular fa-pen"></i>
                                            <!--begin::Inputs-->
                                            <input type="file" class="upload-logo"
                                                   accept=".png, .jpg, .jpeg, .ico"/>
                                            <input type="hidden" name="logo" value="/favicon.ico"/>
                                            <!--end::Inputs-->
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Cancel-->
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                              data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                              title="Cancel logo">
																<i class="fa-duotone fa-regular fa-xmark"></i>
															</span>
                                        <!--end::Cancel-->
                                    </div>
                                    <!--end::Image input-->
                                    <!--begin::Hint-->
                                    <div class="form-text">支持文件格式: png, jpg, jpeg,
                                        ico，更换后，记得强制刷新浏览器，否则你看到的还是缓存。
                                    </div>
                                    <!--end::Hint-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->


                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">关闭请求日志(建议开启)</label>
                                <!--begin::Label-->
                                <!--begin::Label-->
                                <div class="col-lg-8 d-flex align-items-center">
                                    <div class="form-check form-check-solid form-switch fv-row">
                                        <input class="form-check-input w-45px h-30px" type="checkbox"
                                               name="request_log"
                                               value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['request_log'] == 1) {?> checked <?php }?>/>
                                        <label class="form-check-label"></label>
                                    </div>

                                </div>
                                <!--begin::Hint-->
                                <div class="form-text text-success fw-bold">
                                    此配置为临时选项，将在下一个版本中移除。当前我们正在对程序进行大幅优化。为便于排查现阶段系统可能出现的问题，本版本将开启请求日志功能，以协助技术人员更快速、准确地定位问题。如您的网站出现异常，请及时联系官方人员，我们将结合相关日志信息为您进行排查与处理。
                                </div>
                                <!--end::Hint-->
                                <!--begin::Label-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">PC模板</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Input-->
                                    <select name="user_theme" class="form-select form-select-solid form-select-lg"
                                            data-control="select2" data-hide-search="true">
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['themes']->value, 'theme');
$_smarty_tpl->tpl_vars['theme']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['theme']->value) {
$_smarty_tpl->tpl_vars['theme']->do_else = false;
?>
                                            <option value="<?php echo $_smarty_tpl->tpl_vars['theme']->value['info']['KEY'];?>
" <?php if ($_smarty_tpl->tpl_vars['theme']->value['info']['KEY'] == $_smarty_tpl->tpl_vars['config']->value['user_theme']) {?> selected <?php }?>>
                                                <?php echo $_smarty_tpl->tpl_vars['theme']->value['info']['NAME'];?>
 (v<?php echo $_smarty_tpl->tpl_vars['theme']->value['info']['VERSION'];?>
)
                                                <?php if ($_smarty_tpl->tpl_vars['theme']->value['have_update'] === true) {?>
                                                    -> 有更新(v<?php echo $_smarty_tpl->tpl_vars['theme']->value['update_version'];?>
) ‹ 请前往应用商店更新 ›
                                                <?php }?>
                                            </option>
                                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <div class="col-lg-2 fv-row">
                                    <!--begin::Input-->
                                    <button type="button" class="btn btn-light theme-setting"><i class="fa-duotone fa-regular fa-folder-gear"></i> 模板设置
                                    </button>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">手机模板</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Input-->
                                    <select name="user_mobile_theme"
                                            class="form-select form-select-solid form-select-lg"
                                            data-control="select2" data-hide-search="true">
                                        <option value="0" <?php if ("0" == $_smarty_tpl->tpl_vars['config']->value['user_mobile_theme']) {?>selected <?php }?>>跟随PC模板
                                        </option>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['themes']->value, 'theme');
$_smarty_tpl->tpl_vars['theme']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['theme']->value) {
$_smarty_tpl->tpl_vars['theme']->do_else = false;
?>
                                            <option value="<?php echo $_smarty_tpl->tpl_vars['theme']->value['info']['KEY'];?>
" <?php if ($_smarty_tpl->tpl_vars['theme']->value['info']['KEY'] == $_smarty_tpl->tpl_vars['config']->value['user_mobile_theme']) {?>
                                            selected <?php }?>><?php echo $_smarty_tpl->tpl_vars['theme']->value['info']['NAME'];?>
 (v<?php echo $_smarty_tpl->tpl_vars['theme']->value['info']['VERSION'];?>
)
                                                <?php if ($_smarty_tpl->tpl_vars['theme']->value['have_update'] === true) {?>
                                                    -> 有更新(v<?php echo $_smarty_tpl->tpl_vars['theme']->value['update_version'];?>
) ‹ 请前往应用商店更新 ›
                                                <?php }?>
                                            </option>
                                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <div class="col-lg-2 fv-row">
                                    <!--begin::Input-->
                                    <button type="button" class="btn btn-light theme-mobile-setting"><i
                                                class="fa-duotone fa-regular fa-folder-gear"></i> 模板设置
                                    </button>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">会员中心</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Input-->
                                    <select name="user_center_theme" class="form-select form-select-solid form-select-lg"
                                            data-control="select2" data-hide-search="true">
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['themes']->value, 'theme');
$_smarty_tpl->tpl_vars['theme']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['theme']->value) {
$_smarty_tpl->tpl_vars['theme']->do_else = false;
?>
                                            <option value="<?php echo $_smarty_tpl->tpl_vars['theme']->value['info']['KEY'];?>
" <?php if ($_smarty_tpl->tpl_vars['theme']->value['info']['KEY'] == $_smarty_tpl->tpl_vars['config']->value['user_center_theme']) {?> selected <?php }?>>
                                                <?php echo $_smarty_tpl->tpl_vars['theme']->value['info']['NAME'];?>
 (v<?php echo $_smarty_tpl->tpl_vars['theme']->value['info']['VERSION'];?>
)
                                                <?php if ($_smarty_tpl->tpl_vars['theme']->value['have_update'] === true) {?>
                                                    -> 有更新(v<?php echo $_smarty_tpl->tpl_vars['theme']->value['update_version'];?>
) ‹ 请前往应用商店更新 ›
                                                <?php }?>
                                            </option>
                                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <div class="col-lg-2 fv-row">
                                    <!--begin::Input-->
                                    <button type="button" class="btn btn-light theme-user-setting"><i class="fa-duotone fa-regular fa-folder-gear"></i> 模板设置
                                    </button>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">CDN获取IP方式</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Input-->
                                    <select name="ip_get_mode" class="form-select form-select-solid form-select-lg"
                                            data-control="select2" data-hide-search="true">
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ip_get_mode']->value, 'mode', false, 'index');
$_smarty_tpl->tpl_vars['mode']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['index']->value => $_smarty_tpl->tpl_vars['mode']->value) {
$_smarty_tpl->tpl_vars['mode']->do_else = false;
?>
                                            <option value="<?php echo $_smarty_tpl->tpl_vars['index']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['index']->value == $_smarty_tpl->tpl_vars['ip_mode']->value) {?> selected <?php }?>><?php echo $_smarty_tpl->tpl_vars['mode']->value;?>
</option>
                                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">PC背景图片(支持http地址或者图床api)</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" name="background_url"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="请输入背景图床地址，可以是url"
                                           value="<?php echo $_smarty_tpl->tpl_vars['config']->value['background_url'];?>
"/>
                                </div>
                                <div class="col-lg-2 fv-row">
                                    <!--begin::Input-->
                                    <input type="file" class="background-upload" style="display: none;"
                                           accept=".png, .jpg, .jpeg, .gif"/>
                                    <button type="button" class="btn btn-light background-upload"
                                            onclick="document.getElementsByClassName('background-upload')[0].click();">
                                        <i class="fa-duotone fa-regular fa-cloud-arrow-up"></i> 上传图片
                                    </button>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">手机背景图片</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" name="background_mobile_url"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="不设置则使用PC版配置" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['background_mobile_url'];?>
"/>
                                </div>
                                <div class="col-lg-2 fv-row">
                                    <!--begin::Input-->
                                    <input type="file" class="background-mobile-upload" style="display: none;"
                                           accept=".png, .jpg, .jpeg, .gif"/>
                                    <button type="button" class="btn btn-light background-mb-upload"
                                            onclick="document.getElementsByClassName('background-mobile-upload')[0].click();">
                                        <i class="fa-duotone fa-regular fa-cloud-arrow-up"></i> 上传图片
                                    </button>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">店铺名称</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="shop_name"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="请输入店铺名称" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['shop_name'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">网站标题</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="title"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="请输入网站标题" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['title'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label  fw-bold fs-6">关键词</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="keywords"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="请输入网站关键词" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['keywords'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label  fw-bold fs-6">网站描述</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <textarea name="description"
                                              class="form-control form-control-lg form-control-solid"
                                              placeholder="请输入网站描述"><?php echo $_smarty_tpl->tpl_vars['config']->value['description'];?>
</textarea>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label  fw-bold fs-6">店铺公告</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <div class="editor-wrapper">
                                        <div>
                                            <button data-type="0" class="button-switch-notice" type="button"
                                                    style="width: 100%;border: none;background: rgba(255, 255, 255, 0.35);border-radius: 5px 5px 0 0;color: #c9b8b8;">
                                                <i class="fa-duotone fa-regular fa-code me-1"></i>HTML
                                            </button>
                                        </div>
                                        <div class="editor-content">
                                            <div class="toolbar-container"></div>
                                            <div class="editor-container"></div>
                                        </div>
                                        <textarea class="text-container" style="display: none;"
                                                  name="notice"></textarea>
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">注册方式</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <!--begin::Input-->
                                    <select name="registered_type" class="form-select form-select-solid form-select-lg"
                                            data-control="select2" data-hide-search="true">
                                        <option value="0" <?php if ($_smarty_tpl->tpl_vars['config']->value['registered_type'] == 0) {?> selected <?php }?>>用户名
                                        </option>
                                        <option value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['registered_type'] == 1) {?> selected <?php }?>>用户名+手机
                                        </option>
                                        <option value="2" <?php if ($_smarty_tpl->tpl_vars['config']->value['registered_type'] == 2) {?> selected <?php }?>>用户名+邮箱
                                        </option>
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">注册验证码</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <!--begin::Options-->
                                    <div class="d-flex align-items-center mt-3">
                                        <!--begin::Option-->
                                        <label class="form-check form-check-inline form-check-solid me-5">
                                            <input class="form-check-input" name="registered_verification"
                                                   type="checkbox"
                                                   value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['registered_verification'] == 1) {?> checked <?php }?>>
                                            <span class="fw-bold ps-2 fs-6">人机验证</span>
                                        </label>
                                        <!--end::Option-->
                                        <!--begin::Option-->
                                        <label class="form-check form-check-inline form-check-solid">
                                            <input class="form-check-input" name="registered_phone_verification"
                                                   type="checkbox"
                                                   value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['registered_phone_verification'] == 1) {?> checked <?php }?>>
                                            <span class="fw-bold ps-2 fs-6">手机验证码</span>
                                        </label>
                                        <!--end::Option-->
                                        <!--begin::Option-->
                                        <label class="form-check form-check-inline form-check-solid">
                                            <input class="form-check-input" name="registered_email_verification"
                                                   type="checkbox"
                                                   value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['registered_email_verification'] == 1) {?> checked <?php }?>>
                                            <span class="fw-bold ps-2 fs-6">邮箱验证码</span>
                                        </label>
                                        <!--end::Option-->
                                    </div>
                                    <!--end::Options-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">其他验证码</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <!--begin::Options-->
                                    <div class="d-flex align-items-center mt-3">
                                        <!--begin::Option-->
                                        <label class="form-check form-check-inline form-check-solid me-5">
                                            <input class="form-check-input" name="login_verification" type="checkbox"
                                                   value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['login_verification'] == 1) {?> checked <?php }?>>
                                            <span class="fw-bold ps-2 fs-6">登录验证码</span>
                                        </label>
                                        <!--end::Option-->
                                        <!--begin::Option-->
                                        <label class="form-check form-check-inline form-check-solid me-5">
                                            <input class="form-check-input" name="trade_verification" type="checkbox"
                                                   value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['trade_verification'] == 1) {?> checked <?php }?>>
                                            <span class="fw-bold ps-2 fs-6">下单验证码</span>
                                        </label>
                                        <!--end::Option-->
                                    </div>
                                    <!--end::Options-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">注册开关</label>
                                <!--begin::Label-->
                                <!--begin::Label-->
                                <div class="col-lg-8 d-flex align-items-center">
                                    <div class="form-check form-check-solid form-switch fv-row">
                                        <input class="form-check-input w-45px h-30px" type="checkbox"
                                               name="registered_state"
                                               value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['registered_state'] == 1) {?> checked <?php }?>/>
                                        <label class="form-check-label"></label>
                                    </div>
                                </div>
                                <!--begin::Label-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label  fw-bold fs-6">注册用户名长度</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="number" name="username_len"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="请输入限制注册用户名长度" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['username_len'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->


                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">找回密码方式</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <!--begin::Input-->
                                    <select name="forget_type" class="form-select form-select-solid form-select-lg"
                                            data-control="select2" data-hide-search="true">
                                        <option value="0" <?php if ($_smarty_tpl->tpl_vars['config']->value['forget_type'] == 0) {?> selected <?php }?>>邮箱验证码
                                        </option>
                                        <option value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['forget_type'] == 1) {?> selected <?php }?>>手机验证码
                                        </option>
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label  fw-bold fs-6">会话保持时间</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="number" name="session_expire"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="请输入会话保持时间，单位/秒" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['session_expire'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">店铺维护</label>
                                <!--begin::Label-->
                                <!--begin::Label-->
                                <div class="col-lg-8 d-flex align-items-center">
                                    <div class="form-check form-check-solid form-switch fv-row">
                                        <input class="form-check-input w-45px h-30px" type="checkbox"
                                               name="closed" value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['closed'] == 1) {?> checked <?php }?>/>
                                        <label class="form-check-label"></label>
                                    </div>
                                </div>
                                <!--begin::Label-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label  fw-bold fs-6">店铺维护公告</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <textarea name="closed_message"
                                              class="form-control form-control-lg form-control-solid"
                                              placeholder="请输入店铺维护公告"><?php echo $_smarty_tpl->tpl_vars['config']->value['closed_message'];?>
</textarea>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Card body-->
                        <!--begin::Actions-->
                        <div class="card-footer d-flex justify-content-center py-6 px-9">
                            <button type="button" class="btn btn-primary save-data">保存设置 (Save Config)</button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Basic info-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->

<?php echo ready("/assets/admin/controller/config/index.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
