<?php
/* Smarty version 3.1.46, created on 2026-05-01 13:30:09
  from 'D:\phpstudy_pro\WWW\app\View\Admin\User\Order.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f43a615e8d39_58729137',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f800daa3fe588aaa6731ee44c3856794bedb8f2b' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\Admin\\User\\Order.html',
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
function content_69f43a615e8d39_58729137 (Smarty_Internal_Template $_smarty_tpl) {
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
            <!--begin::Row-->
            <div class="row g-5 g-xl-10">
                <div class="col-xl-3">
                    <!--begin::Statistics Widget 1-->
                    <div class="card bgi-no-repeat card-xl-stretch mb-xl-8"
                         style="background-position: right top; background-size: 30% auto; background-image: url(/assets/admin/images/svg/shapes/abstract-4.svg)">
                        <!--begin::Body-->
                        <div class="card-body">
                            <span class="card-title fw-bolder text-muted text-hover-primary fs-4">订单数量</span>
                            <div class="fw-bolder fs-1 text-primary my-6 order_count">...</div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 1-->
                </div>
                <div class="col-xl-3">
                    <!--begin::Statistics Widget 1-->
                    <div class="card bgi-no-repeat card-xl-stretch mb-xl-8"
                         style="background-position: right top; background-size: 30% auto; background-image: url(/assets/admin/images/svg/shapes/abstract-4.svg)">
                        <!--begin::Body-->
                        <div class="card-body">
                            <span class="card-title fw-bolder text-muted text-hover-primary fs-4">订单金额</span>
                            <div class="fw-bolder fs-1 text-success my-6 order_amount">...</div>
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
                <div class="card-header">
                    <div class="card-toolbar">
                        <button class="btn btn-sm btn-light-primary clear me-3">
                            <i class="fa-duotone fa-regular fa-trash-can-clock"></i> 一键清理无用订单
                        </button>
                        <button class="btn btn-sm btn-light-success btn-app-export me-3"><i class="fa-duotone fa-regular fa-down-to-line"></i>
                            导出筛选订单
                        </button>
                    </div>
                </div>
                <!--end::Header-->
                <div class="card-body py-3">
                    <table id="recharge-order-table" ></table>
                </div>
            </div>
            <!--end::Tables Widget 9-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->
<?php echo ready("/assets/admin/controller/user/recharge.order.js");?>

<!--start::HOOK-->
<?php echo hook(\App\Consts\Hook::ADMIN_VIEW_ORDER_FOOTER);?>

<!--end::HOOK-->
<?php $_smarty_tpl->_subTemplateRender("file:../Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
