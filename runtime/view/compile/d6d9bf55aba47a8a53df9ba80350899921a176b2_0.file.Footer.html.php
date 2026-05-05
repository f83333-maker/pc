<?php
/* Smarty version 3.1.46, created on 2026-05-03 15:28:52
  from '/www/wwwroot/pcccc.cc/app/View/User/Theme/Cartoon/Index/Footer.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f6f9344b83e7_09492734',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd6d9bf55aba47a8a53df9ba80350899921a176b2' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Theme/Cartoon/Index/Footer.html',
      1 => 1777788801,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f6f9344b83e7_09492734 (Smarty_Internal_Template $_smarty_tpl) {
?></div>
<footer class="site-footer">
    <div class="container">
        <div class="footer-top">
            <div class="row">
                <!-- 左侧：Logo 和 简介 -->
                <div class="col-lg-5 footer-brand">
                    <div class="footer-logo">
                        <img src="/favicon.ico" alt="Logo" loading="lazy" decoding="async">
                        <span><?php echo $_smarty_tpl->tpl_vars['config']->value['shop_name'];?>
</span>
                    </div>
                    <p class="footer-desc">专业的在线工作室，为您提供优质的产品和服务。助力全球化业务拓展，一站式跨境资源采购平台。</p>
                </div>
                
                <!-- 中间：快速链接 -->
                <div class="col-lg-3 footer-links">
                    <h6 class="footer-title">快速链接</h6>
                    <ul>
                        <li><a href="/"><i class="fa-duotone fa-regular fa-house"></i> 首页</a></li>
                        <li><a href="/user/index/query"><i class="fa-duotone fa-regular fa-rectangle-list"></i> 订单查询</a></li>
                        <li><a href="/user/index/twofa"><i class="fa-duotone fa-regular fa-shield-keyhole"></i> 2FA验证</a></li>
                    </ul>
                </div>
                
                <!-- 右侧：联系我们 -->
                <div class="col-lg-4 footer-contact">
                    <h6 class="footer-title">联系我们</h6>
                    <ul class="contact-list">
                        <li><a href="#" target="_blank"><i class="fa-duotone fa-regular fa-paper-plane"></i> Telegram</a></li>
                        <li><a href="#" target="_blank"><i class="fa-duotone fa-regular fa-messages"></i> QQ在线客服</a></li>
                        <li><a href="mailto:#" target="_blank"><i class="fa-duotone fa-regular fa-envelope"></i> 官方邮箱</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="footer-copyright">
                © 2026 <?php echo $_smarty_tpl->tpl_vars['config']->value['shop_name'];?>
. 保留所有权利 <?php if ($_smarty_tpl->tpl_vars['setting']->value['icp']) {?>| <?php echo $_smarty_tpl->tpl_vars['setting']->value['icp'];
}?>
            </div>
            <div class="footer-legal">
                <a href="#">隐私政策</a>
                <a href="#">服务条款</a>
            </div>
        </div>
    </div>
</footer>

<?php echo js(array("/assets/common/js/_.js","/assets/user/js/_index.js"),array("/assets/common/js/util/dict.js","/assets/common/js/jquery.min.js","/assets/common/js/toastr.min.js","/assets/common/js/component/loading.js","/assets/common/js/util.js","/assets/common/js/layer/layer.js","/assets/common/js/jquery.pjax.min.js","/assets/common/js/jquery.qrcode.min.js","/assets/common/js/format.js","/assets/common/js/message.js","/assets/common/js/component.js","/assets/common/js/layui/layui.js","/assets/common/js/jquery.treegrid.min.js","/assets/common/js/bootstrap/bootstrap.bundle.min.js","/assets/common/js/table/bootstrap-table.min.js","/assets/common/js/table/bootstrap-table-treegrid.min.js","/assets/common/js/component/form.js","/assets/common/js/component/search.js","/assets/common/js/component/xm-select.js","/assets/common/js/component/tree.select.js","/assets/common/js/component/authtree.js","/assets/common/js/component/table.js","/assets/common/js/component/select2.min.js","/assets/common/js/cache.js","/assets/common/js/editor/editor.js","/assets/common/js/editor/code/code.js","/assets/common/js/component/decimal.js","/assets/user/js/trade.js","/assets/user/js/treasure.js"));?>

<!--start::HOOK-->
<?php echo hook(\App\Consts\Hook::USER_GLOBAL_VIEW_BODY);?>

<?php echo hook(\App\Consts\Hook::USER_VIEW_INDEX_BODY);?>

<!--end::HOOK-->
</body>
<!--start::HOOK-->
<?php echo hook(\App\Consts\Hook::USER_GLOBAL_VIEW_FOOTER);?>

<?php echo hook(\App\Consts\Hook::USER_VIEW_INDEX_FOOTER);?>

<!--end::HOOK-->
</html><?php }
}
