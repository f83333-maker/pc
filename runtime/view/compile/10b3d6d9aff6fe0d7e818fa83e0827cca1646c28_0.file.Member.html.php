<?php
/* Smarty version 3.1.46, created on 2026-05-01 21:27:57
  from '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/Agent/Member.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f4aa5d99a4f2_79639949',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '10b3d6d9aff6fe0d7e818fa83e0827cca1646c28' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/Agent/Member.html',
      1 => 1776730478,
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
function content_69f4aa5d99a4f2_79639949 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Common/Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<div class="mf-page-shell" data-mf-controller="/assets/user/controller/user/child.js" data-mf-table="#member-table">
    <?php $_smarty_tpl->_subTemplateRender("file:../Common/Nav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <main class="mf-main">
        <section class="mf-panel">
            <div class="mf-panel-heading">
                <span><i class="fa-duotone fa-regular fa-users-viewfinder"></i> 我的下级</span>
            </div>
            <div class="mf-table-wrap">
                <table id="member-table"></table>
            </div>
        </section>
    </main>
</div>
<?php echo ready("/assets/user/controller/user/child.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Common/Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
