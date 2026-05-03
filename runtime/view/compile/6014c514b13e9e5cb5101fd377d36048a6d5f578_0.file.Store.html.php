<?php
/* Smarty version 3.1.46, created on 2026-05-01 18:35:01
  from '/www/wwwroot/pcccc.cc/app/View/Admin/Store/Store.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f481d53ca715_26999787',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6014c514b13e9e5cb5101fd377d36048a6d5f578' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/Admin/Store/Store.html',
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
function content_69f481d53ca715_26999787 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid store-content" id="kt_content">
    <!--begin::Toolbar-->
    <?php $_smarty_tpl->_subTemplateRender("file:../Toolbar.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-fluid">
            <!--begin::Tables Widget 9-->
            <div class="card mb-5 mb-xl-8">
                <!--begin::Header-->
                <div class="card-header border-0 store-toolbar" style="display: none;">
                    <div class="card-toolbar">
                        <button class="btn btn-sm btn-light-danger me-3 update-pro" style="display: none;"><i class="fa-duotone fa-regular fa-circle-yen"></i>开通企业版(免费使用所有插件/获得技术支持)
                        </button>
                        <button class="btn btn-sm btn-light-primary me-3 bind-pro" style="display: none;"><i class="fa-duotone fa-regular fa-lock-hashtag"></i> 绑定专业版/企业版
                        </button>
                    </div>
                </div>
                <!--end::Header-->

                <div class="card-body py-3">
                    <table id="plugin-table"></table>
                </div>
            </div>

            <!--end::Tables Widget 9-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->

<?php echo ready("/assets/admin/controller/store/home.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
