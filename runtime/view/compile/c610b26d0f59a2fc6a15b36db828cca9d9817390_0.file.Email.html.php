<?php
/* Smarty version 3.1.46, created on 2026-05-02 23:50:45
  from '/www/wwwroot/pcccc.cc/app/View/Admin/Config/Email.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f61d558aaf31_19503703',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c610b26d0f59a2fc6a15b36db828cca9d9817390' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/Admin/Config/Email.html',
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
function content_69f61d558aaf31_19503703 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

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
                        <h3 class="fw-bolder m-0">SMTP配置</h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--begin::Card header-->
                <!--begin::Content-->
                <div id="kt_account_profile_details" class="collapse show">
                    <!--begin::Form-->
                    <form id="data-form" class="form">
                        <!--begin::Card body-->
                        <div class="card-body p-9">
                            <!--begin::Alert-->
                            <div class="alert alert-dismissible bg-light-primary d-flex flex-column flex-sm-row p-5 mb-10">
                                <!--begin::Icon-->
                                <span class="me-4 mb-5 mb-sm-0"><i class="fa-duotone fa-regular fa-bell"></i></span>
                                <!--end::Icon-->
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-column pe-0 pe-sm-10">
                                    <!--begin::Content-->
                                    <span>本系统默认内置SMTP发信功能，如果你想使用其他邮件服务，请通过安装插件来使用。</span>
                                    <!--end::Content-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Alert-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">SMTP服务器</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="smtp"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="请输入smtp服务器地址" value="<?php echo $_smarty_tpl->tpl_vars['email']->value['smtp'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">通信加密协议</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <!--begin::Input-->
                                    <select name="secure" class="form-select form-select-solid form-select-lg"
                                            data-control="select2" data-hide-search="true">
                                        <option value="0" <?php if ($_smarty_tpl->tpl_vars['email']->value['secure'] == 0) {?> selected <?php }?>>SSL</option>
                                        <option value="1" <?php if ($_smarty_tpl->tpl_vars['email']->value['secure'] == 1) {?> selected <?php }?>>TLS</option>  
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">端口</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="port"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="请输入端口" value="<?php echo $_smarty_tpl->tpl_vars['email']->value['port'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->


                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">用户名</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="username"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="请输入用户名" value="<?php echo $_smarty_tpl->tpl_vars['email']->value['username'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->


                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">授权码</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="password"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="请输入授权码" value="<?php echo $_smarty_tpl->tpl_vars['email']->value['password'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                        </div>
                        <!--end::Card body-->
                        <!--begin::Actions-->
                        <div class="card-footer d-flex justify-content-center py-6 px-9">
                            <button type="button" class="btn btn-success send-test-message m-1">发送测试邮件 (Send Test
                                Message)
                            </button>
                            <button type="button" class="btn btn-primary save-data m-1">保存设置 (Save Config)</button>
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


<?php echo ready("/assets/admin/controller/config/mail.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
