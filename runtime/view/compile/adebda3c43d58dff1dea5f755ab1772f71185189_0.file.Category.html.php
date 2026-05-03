<?php
/* Smarty version 3.1.46, created on 2026-04-30 22:20:38
  from 'D:\phpstudy_pro\WWW\app\View\Admin\Trade\Category.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f365368e7245_04020666',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'adebda3c43d58dff1dea5f755ab1772f71185189' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\Admin\\Trade\\Category.html',
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
function content_69f365368e7245_04020666 (Smarty_Internal_Template $_smarty_tpl) {
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
                        <button class="btn btn-sm btn-light-primary btn-app-create me-3"><i
                                    class="fa-duotone fa-regular fa-circle-plus"></i>
                            添加分类
                        </button>
                        <button class="btn btn-sm btn-light-success start me-3"><i
                                    class="fa-duotone fa-regular fa-circle-play"></i>
                            启用选中分类
                        </button>
                        <button class="btn btn-sm btn-light-dark stop me-3"><i
                                    class="fa-duotone fa-regular fa-circle-stop"></i>
                            停用选中分类
                        </button>
                        <button class="btn btn-sm btn-light-danger btn-app-del me-3"><i
                                    class="fa-duotone fa-regular fa-trash-can"></i> 移除选中分类
                        </button>
                        <!--start::HOOK-->
                        <?php echo hook(\App\Consts\Hook::ADMIN_VIEW_CATEGORY_TOOLBAR);?>

                        <!--end::HOOK-->
                    </div>
                </div>
                <!--end::Header-->
                <div class="card-body py-3">
                    <table id="category-table"></table>
                </div>
            </div>

            <!--end::Tables Widget 9-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->
<?php echo ready("/assets/admin/controller/trade/category.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
