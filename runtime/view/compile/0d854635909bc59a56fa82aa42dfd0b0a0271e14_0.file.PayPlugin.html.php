<?php
/* Smarty version 3.1.46, created on 2026-05-02 21:46:52
  from '/www/wwwroot/pcccc.cc/app/View/Admin/Config/PayPlugin.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f6004c02dd31_52705937',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0d854635909bc59a56fa82aa42dfd0b0a0271e14' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/Admin/Config/PayPlugin.html',
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
function content_69f6004c02dd31_52705937 (Smarty_Internal_Template $_smarty_tpl) {
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
        <div id="kt_content_container" class="container-fluid">
            <!--begin::Tables Widget 9-->
            <div class="card mb-5 mb-xl-8">
                <!--begin::Header-->
                <div class="card-header border-0">
                    <div class="card-toolbar">
                        <a href="/admin/store/home" class="btn btn-sm btn-light-primary btn-app-create me-3"><i
                                    class="fa-duotone fa-regular fa-rectangle-history-circle-plus"></i>
                            安装更多插件
                        </a>
                    </div>
                </div>
                <!--end::Header-->
                <div class="card-body py-3">
                    <table id="pay-plugin-table"></table>
                </div>
            </div>

            <!--end::Tables Widget 9-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->
<?php echo ready("/assets/admin/controller/pay/plugin.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
