<?php
/* Smarty version 3.1.46, created on 2026-05-01 02:15:38
  from 'D:\phpstudy_pro\WWW\app\View\Admin\User\Group.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f39c4a9c0a96_83666432',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ea2b79f5b9aea43ce72f971bd6f03bd51bb22db6' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\Admin\\User\\Group.html',
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
function content_69f39c4a9c0a96_83666432 (Smarty_Internal_Template $_smarty_tpl) {
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
            <div class="row">
                <div class="card mb-5 mb-xl-8 col-md-6 p-0">
                    <!--begin::Header-->
                    <div class="card-header border-0">
                        <h3 class="card-title text-primary"><i class="fa-duotone fa-regular fa-users-gear me-2"></i> 会员等级</h3>
                        <div class="card-toolbar">
                            <button class="btn btn-sm btn-light-primary btn-group-create me-3"><i
                                        class="fa-duotone fa-regular fa-circle-plus"></i>新增等级
                            </button>
                        </div>
                    </div>
                    <!--end::Header-->
                    <div class="card-body py-3">
                        <table id="user-group"></table>
                    </div>
                </div>
                <!--end::Tables Widget 9-->

                <!--begin::Tables Widget 9-->
                <div class="card mb-5 mb-xl-8 col-md-5 p-0 ms-md-2">
                    <!--begin::Header-->
                    <div class="card-header border-0">
                        <h3 class="card-title text-info"><i class="fa-duotone fa-regular fa-group-arrows-rotate me-2"></i> 商品分组</h3>
                        <div class="card-toolbar">
                            <button class="btn btn-sm btn-light-primary btn-commodity-group-create me-3"><i
                                        class="fa-duotone fa-regular fa-circle-plus"></i>
                                添加分组
                            </button>
                        </div>
                    </div>
                    <!--end::Header-->

                    <div class="card-body py-3">
                        <form class="search-query"></form>
                        <table id="commodity-group" ></table>
                    </div>
                </div>
            </div>
            <!--end::Tables Widget 9-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->

<?php echo ready("/assets/admin/controller/user/group.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
