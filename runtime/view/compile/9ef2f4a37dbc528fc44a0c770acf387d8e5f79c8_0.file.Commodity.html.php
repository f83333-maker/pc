<?php
/* Smarty version 3.1.46, created on 2026-05-01 17:08:55
  from '/www/wwwroot/pcccc.cc/app/View/Admin/Trade/Commodity.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f46da7b948e6_28786951',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9ef2f4a37dbc528fc44a0c770acf387d8e5f79c8' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/Admin/Trade/Commodity.html',
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
function content_69f46da7b948e6_28786951 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <?php $_smarty_tpl->_subTemplateRender("file:../Toolbar.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-fluid">
            <!--begin::Row-->
            <div class="row g-5 g-xl-10">
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 1-->
                    <div class="card bgi-no-repeat card-xl-stretch mb-xl-8"
                         style="background-position: right top; background-size: 30% auto; background-image: url(/assets/admin/images/svg/shapes/abstract-4.svg)">
                        <!--begin::Body-->
                        <div class="card-body">
                            <span class="card-title fw-bolder text-muted text-hover-primary fs-4">总商品</span>
                            <div class="fw-bolder fs-1 text-primary my-6"><?php echo $_smarty_tpl->tpl_vars['all']->value;?>
</div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 1-->
                </div>
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 1-->
                    <div class="card bgi-no-repeat card-xl-stretch mb-xl-8"
                         style="background-position: right top; background-size: 30% auto; background-image: url(/assets/admin/images/svg/shapes/abstract-2.svg)">
                        <!--begin::Body-->
                        <div class="card-body">
                            <span class="card-title fw-bolder text-muted text-hover-primary fs-4">已上架</span>
                            <div class="fw-bolder fs-1 text-success my-6"><?php echo $_smarty_tpl->tpl_vars['shelves']->value;?>
</div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 1-->
                </div>
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 1-->
                    <div class="card bgi-no-repeat card-xl-stretch mb-5 mb-xl-8"
                         style="background-position: right top; background-size: 30% auto; background-image: url(/assets/admin/images/svg/shapes/abstract-1.svg)">
                        <!--begin::Body-->
                        <div class="card-body">
                            <span class="card-title fw-bolder text-muted text-hover-primary fs-4">未上架</span>
                            <div class="fw-bolder fs-1 text-danger my-6"><?php echo $_smarty_tpl->tpl_vars['not']->value;?>
</div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 1-->
                </div>

                <div class="col-xl-2">
                    <!--begin::Statistics Widget 1-->
                    <div class="card bgi-no-repeat card-xl-stretch mb-5 mb-xl-8"
                         style="background-position: right top; background-size: 30% auto; background-image: url(/assets/admin/images/svg/shapes/abstract-3.svg)">
                        <!--begin::Body-->
                        <div class="card-body">
                            <span class="card-title fw-bolder text-muted text-hover-primary fs-4">主站商品</span>
                            <div class="fw-bolder fs-1 text-info my-6"><?php echo $_smarty_tpl->tpl_vars['main']->value;?>
</div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 1-->
                </div>
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 1-->
                    <div class="card bgi-no-repeat card-xl-stretch mb-5 mb-xl-8"
                         style="background-position: right top; background-size: 30% auto; background-image: url(/assets/admin/images/svg/shapes/abstract-4.svg)">
                        <!--begin::Body-->
                        <div class="card-body">
                            <span class="card-title fw-bolder text-muted text-hover-primary fs-4">子站商品</span>
                            <div class="fw-bolder fs-1 text-dark my-6"><?php echo $_smarty_tpl->tpl_vars['child']->value;?>
</div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 1-->
                </div>
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 1-->
                    <div class="card bgi-no-repeat card-xl-stretch mb-5 mb-xl-8"
                         style="background-position: right top; background-size: 30% auto; background-image: url(/assets/admin/images/svg/shapes/abstract-4.svg)">
                        <!--begin::Body-->
                        <div class="card-body">
                            <span class="card-title fw-bolder text-muted text-hover-primary fs-4">子站上架</span>
                            <div class="fw-bolder fs-1 text-success my-6"><?php echo $_smarty_tpl->tpl_vars['child_shelves']->value;?>
</div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 1-->
                </div>
            </div>
            <!--end::Row-->

            <!--begin::Tables Widget 9-->
            <div class="card mb-5 mb-xl-8">
                <!--begin::Header-->
                <div class="card-header border-0">
                    <div class="card-toolbar">
                        <button class="btn btn-sm btn-light-primary btn-app-create me-3"><i class="fa-duotone fa-regular fa-circle-plus"></i>
                            添加商品
                        </button>
                        <button class="btn btn-sm btn-light-success listed me-3"><i class="fa-duotone fa-regular fa-up-from-bracket"></i>
                            上架选中商品
                        </button>
                        <button class="btn btn-sm btn-light-dark delist me-3"><i class="fa-duotone fa-regular fa-down-to-bracket"></i>
                            下架选中商品
                        </button>
                        <button class="btn btn-sm btn-light-primary handle me-3"><i class="fa-duotone fa-regular fa-rotate"></i>
                            一键操作选中商品
                        </button>
                        <button class="btn btn-sm btn-light-danger btn-app-del me-3"><i class="fa-duotone fa-regular fa-trash-can"></i> 移除选中商品
                        </button>
                        <!--start::HOOK-->
                        <?php echo hook(\App\Consts\Hook::ADMIN_VIEW_COMMODITY_TOOLBAR);?>

                        <!--end::HOOK-->
                    </div>
                </div>
                <!--end::Header-->
                <div class="card-body py-3">
                    <table id="commodity-table"></table>
                </div>
            </div>

            <!--end::Tables Widget 9-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->
<!--start::HOOK-->
<?php echo hook(\App\Consts\Hook::ADMIN_VIEW_COMMODITY_FOOTER);?>

<!--end::HOOK-->
<?php echo ready("/assets/admin/controller/trade/commodity.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
