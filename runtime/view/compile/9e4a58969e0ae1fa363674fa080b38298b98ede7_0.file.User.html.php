<?php
/* Smarty version 3.1.46, created on 2026-05-01 02:15:41
  from 'D:\phpstudy_pro\WWW\app\View\Admin\User\User.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f39c4d6ae720_94370813',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9e4a58969e0ae1fa363674fa080b38298b98ede7' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\Admin\\User\\User.html',
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
function content_69f39c4d6ae720_94370813 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<style>
    .layui-popup .layui-layer-content {
        position: relative !important;
        overflow: auto !important;
    }
</style>
<!--start::HOOK-->
<?php echo hook(\App\Consts\Hook::ADMIN_VIEW_USER_HEADER);?>

<!--end::HOOK-->
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
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 1-->
                    <div class="card bgi-no-repeat card-xl-stretch mb-xl-8"
                         style="background-position: right top; background-size: 30% auto; background-image: url(/assets/admin/images/svg/shapes/abstract-4.svg)">
                        <!--begin::Body-->
                        <div class="card-body">
                            <span class="card-title fw-bolder text-muted text-hover-primary fs-4">总用户</span>
                            <div class="fw-bolder fs-1 text-primary my-6"><?php echo $_smarty_tpl->tpl_vars['userCount']->value;?>
</div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 1-->
                </div>
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 1-->
                    <div class="card bgi-no-repeat card-xl-stretch mb-xl-8"
                         style="background-position: right top; background-size: 30% auto; background-image: url(/assets/admin/images/svg/shapes/abstract-4.svg)">
                        <!--begin::Body-->
                        <div class="card-body">
                            <span class="card-title fw-bolder text-muted text-hover-primary fs-4">商家</span>
                            <div class="fw-bolder fs-1 text-success my-6"><?php echo $_smarty_tpl->tpl_vars['businessCount']->value;?>
</div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 1-->
                </div>
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 1-->
                    <div class="card bgi-no-repeat card-xl-stretch mb-xl-8"
                         style="background-position: right top; background-size: 30% auto; background-image: url(/assets/admin/images/svg/shapes/abstract-4.svg)">
                        <!--begin::Body-->
                        <div class="card-body">
                            <span class="card-title fw-bolder text-muted text-hover-primary fs-4">当前余额</span>
                            <div class="fw-bolder fs-1 text-primary my-6">￥<?php echo $_smarty_tpl->tpl_vars['balance']->value;?>
</div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 1-->
                </div>
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 1-->
                    <div class="card bgi-no-repeat card-xl-stretch mb-xl-8"
                         style="background-position: right top; background-size: 30% auto; background-image: url(/assets/admin/images/svg/shapes/abstract-4.svg)">
                        <!--begin::Body-->
                        <div class="card-body">
                            <span class="card-title fw-bolder text-muted text-hover-primary fs-4">总充值</span>
                            <div class="fw-bolder fs-1 text-success my-6"><?php echo $_smarty_tpl->tpl_vars['recharge']->value;?>
</div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 1-->
                </div>
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 1-->
                    <div class="card bgi-no-repeat card-xl-stretch mb-xl-8"
                         style="background-position: right top; background-size: 30% auto; background-image: url(/assets/admin/images/svg/shapes/abstract-4.svg)">
                        <!--begin::Body-->
                        <div class="card-body">
                            <span class="card-title fw-bolder text-muted text-hover-primary fs-4">当前硬币</span>
                            <div class="fw-bolder fs-1 text-primary my-6">￥<?php echo $_smarty_tpl->tpl_vars['coin']->value;?>
</div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Statistics Widget 1-->
                </div>
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 1-->
                    <div class="card bgi-no-repeat card-xl-stretch mb-xl-8"
                         style="background-position: right top; background-size: 30% auto; background-image: url(/assets/admin/images/svg/shapes/abstract-4.svg)">
                        <!--begin::Body-->
                        <div class="card-body">
                            <span class="card-title fw-bolder text-muted text-hover-primary fs-4">总获得硬币</span>
                            <div class="fw-bolder fs-1 text-success my-6"><?php echo $_smarty_tpl->tpl_vars['totalCoin']->value;?>
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
                <div class="card-header">
                    <div class="card-toolbar">
                        <button class="btn btn-sm btn-light-primary handle me-3"><i class="fa-duotone fa-regular fa-user-pen"></i>修改会员等级
                        </button>
                        <button class="btn btn-sm btn-light-danger btn-app-del me-3"><i class="fa-duotone fa-regular fa-trash-can"></i> 移除选中用户 </button>
                        <!--start::HOOK-->
                        <?php echo hook(\App\Consts\Hook::ADMIN_VIEW_USER_TOOLBAR);?>

                        <!--end::HOOK-->
                    </div>
                </div>
                <!--end::Header-->
                <div class="card-body py-3">
                    <table id="user-table"></table>
                </div>
            </div>

            <!--end::Tables Widget 9-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->

<?php echo ready("/assets/admin/controller/user/index.js");?>

<!--start::HOOK-->
<?php echo hook(\App\Consts\Hook::ADMIN_VIEW_USER_FOOTER);?>

<!--end::HOOK-->
<?php $_smarty_tpl->_subTemplateRender("file:../Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
