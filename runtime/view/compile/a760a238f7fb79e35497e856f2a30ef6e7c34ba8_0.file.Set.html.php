<?php
/* Smarty version 3.1.46, created on 2026-05-01 21:33:39
  from '/www/wwwroot/pcccc.cc/app/View/Admin/Manage/Set.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f4abb3d84165_94066314',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a760a238f7fb79e35497e856f2a30ef6e7c34ba8' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/Admin/Manage/Set.html',
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
function content_69f4abb3d84165_94066314 (Smarty_Internal_Template $_smarty_tpl) {
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
        <!--begin::Content-->
        <div id="kt_account_profile_details" class="collapse show">
          <!--begin::Form-->
          <form id="data-form" class="form">
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
              <!--begin::Input group-->
              <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-bold fs-6">头像</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                  <!--begin::Image input-->
                  <div class="image-input image-input-outline" data-kt-image-input="true"
                       style="background-image: url(/assets/admin/images/setting/blank.png)">
                    <!--begin::Preview existing logo-->
                    <div class="image-input-wrapper w-125px h-125px"
                         style="background-image: url('<?php if ($_smarty_tpl->tpl_vars['user']->value['avatar']) {
echo $_smarty_tpl->tpl_vars['user']->value['avatar'];
} else { ?>/favicon.ico<?php }?>')"></div>
                    <!--end::Preview existing logo-->
                    <!--begin::Label-->
                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                           data-kt-image-input-action="change" data-bs-toggle="tooltip"
                           title="Change logo">
                      <i class="fa-duotone fa-regular fa-pen"></i>
                      <!--begin::Inputs-->
                      <input type="file" class="upload-logo"
                             accept=".png, .jpg, .jpeg, .ico"/>
                      <input type="hidden" name="avatar" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['avatar'];?>
"/>
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
                  <div class="form-text">支持文件格式: png, jpg, jpeg, ico.</div>
                  <!--end::Hint-->
                </div>
                <!--end::Col-->
              </div>
              <!--end::Input group-->

              <!--begin::Input group-->
              <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">呢称</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row">
                  <input type="text" name="nickname"
                         class="form-control form-control-lg form-control-solid"
                         placeholder="呢称" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['nickname'];?>
"/>
                </div>
                <!--end::Col-->
              </div>
              <!--end::Input group-->

              <!--begin::Input group-->
              <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label  fw-bold fs-6">旧密码</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row">
                  <input type="password" name="old_password"
                         class="form-control form-control-lg form-control-solid"
                         placeholder="不修改请留空" value=""/>
                </div>
                <!--end::Col-->
              </div>
              <!--end::Input group-->


              <!--begin::Input group-->
              <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label  fw-bold fs-6">新密码</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row">
                  <input type="password" name="password"
                         class="form-control form-control-lg form-control-solid"
                         placeholder="不修改请留空" value=""/>
                </div>
                <!--end::Col-->
              </div>
              <!--end::Input group-->

              <!--begin::Input group-->
              <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label  fw-bold fs-6">确认新密码</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row">
                  <input type="password" name="re_password"
                         class="form-control form-control-lg form-control-solid"
                         placeholder="不修改请留空" value=""/>
                </div>
                <!--end::Col-->
              </div>
              <!--end::Input group-->


            </div>
            <!--end::Card body-->
            <!--begin::Actions-->
            <div class="card-footer d-flex justify-content-center py-6 px-9">
              <button type="button" class="btn btn-light-primary save-data" style="margin-right: 20px;"><i class="fa-duotone fa-regular fa-floppy-disk"></i> 确认修改</button>
              <a href="/admin/authentication/logout" type="button" class="btn btn-light-danger logout"><i class="fa-duotone fa-regular fa-right-from-bracket"></i> 注销登录</a>
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

<?php echo ready("/assets/admin/controller/manage/set.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
