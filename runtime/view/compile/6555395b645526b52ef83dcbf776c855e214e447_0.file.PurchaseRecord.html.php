<?php
/* Smarty version 3.1.46, created on 2026-05-01 02:20:45
  from 'D:\phpstudy_pro\WWW\app\View\User\Theme\Cartoon\User\PurchaseRecord.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f39d7d696392_06213117',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6555395b645526b52ef83dcbf776c855e214e447' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\User\\Theme\\Cartoon\\User\\PurchaseRecord.html',
      1 => 1777554956,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../Common/Header.html' => 1,
    'file:../Common/Nav.html' => 1,
    'file:../Common/Footer.html' => 1,
  ),
),false)) {
function content_69f39d7d696392_06213117 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Common/Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<style>
    .card-view-title {
        color: #a26880;
        font-weight: bolder !important;
    }

    .card-views {
        border: none;
        padding: 20px 20px;
        border-radius: 20px;
        box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    }

    .card-views .card-view {
        padding-top: 2px;
        padding-bottom: 2px;
    }

    .card-views .card-view .secret {
        border: none;
        width: 100%;
        height: 100px;
        padding: 10px;
        border-radius: 5px;
        box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        background: #ffccd52e;
        color: green;
    }

    .card-views .card-view .secret-download {
        color: #0d8ddc;
        border: none;
        padding: 5px;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        font-size: 13px;
    }


</style>

<div class="layui-container fly-marginTop fly-user-main">
    <?php $_smarty_tpl->_subTemplateRender("file:../Common/Nav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <div class="fly-panel fly-panel-user" pad20>
        <div class="layui-tab layui-tab-brief" lay-filter="user">
            <div class="content-header">
                <i class="layui-icon">&#xe638;</i> 购买记录
            </div>
            <div class="content-body">
                <table id="bill-table"></table>
            </div>
        </div>
    </div>
</div>


<?php echo ready("/assets/user/controller/user/purchase.record.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Common/Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
