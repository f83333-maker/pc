<?php
/* Smarty version 3.1.46, created on 2026-05-01 13:31:43
  from 'D:\phpstudy_pro\WWW\app\View\Admin\Trade\Card.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f43abfddf181_01452976',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9959e75ef469245a6320250f1a0c191bbbdc1c7c' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\Admin\\Trade\\Card.html',
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
function content_69f43abfddf181_01452976 (Smarty_Internal_Template $_smarty_tpl) {
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
                        <button class="btn btn-sm btn-light-primary btn-app-create me-3"><i class="fa-duotone fa-regular fa-cloud-arrow-up"></i>
                            上传卡密
                        </button>
                        <button class="btn btn-sm btn-light-danger btn-app-del me-3"><i class="fa-duotone fa-regular fa-trash-can"></i> 移除选中卡密
                        </button>
                        <button class="btn btn-sm btn-light-dark btn-app-lock me-3"><i class="fa-duotone fa-regular fa-lock-hashtag"></i> 锁定选中卡密
                        </button>
                        <button class="btn btn-sm btn-light-success btn-app-unlock me-3"><i class="fa-duotone fa-regular fa-unlock-keyhole"></i>
                            解锁选中卡密
                        </button>
                        <button class="btn btn-sm btn-light-success btn-app-sell me-3"><i class="fa-duotone fa-regular fa-arrows-spin"></i>
                            将卡密更改为已出售
                        </button>
                        <button class="btn btn-sm btn-light-primary btn-app-export me-3"><i class="fa-duotone fa-regular fa-down-to-line"></i>
                            导出筛选卡密
                        </button>
                    </div>
                </div>
                <!--end::Header-->

                <div class="card-body py-3">
                    <table id="card-table"></table>
                </div>
            </div>

            <!--end::Tables Widget 9-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->
<?php echo ready("/assets/admin/controller/trade/card.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
