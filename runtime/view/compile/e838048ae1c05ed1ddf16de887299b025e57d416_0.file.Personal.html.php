<?php
/* Smarty version 3.1.46, created on 2026-05-02 13:51:16
  from '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/User/Personal.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f590d477d387_23496000',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e838048ae1c05ed1ddf16de887299b025e57d416' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/User/Personal.html',
      1 => 1776784510,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../Common/Header.html' => 1,
    'file:../Common/Nav.html' => 1,
    'file:../Common/SecurityNav.html' => 1,
    'file:../Common/Footer.html' => 1,
  ),
),false)) {
function content_69f590d477d387_23496000 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Common/Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<div class="mf-page-shell mf-page-shell-security mf-page-shell-personal">
    <?php $_smarty_tpl->_subTemplateRender("file:../Common/Nav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <main class="mf-main">
        <section class="mf-panel">
            <?php $_smarty_tpl->_subTemplateRender("file:../Common/SecurityNav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <form class="layui-form form-data">
                <div class="form-block">
                    <div>
                        <div class="form-header">头像</div>
                        <div class="form-body">
                            <input type="file" class="avatar-input" style="display:none;">
                            <input type="text" name="avatar" style="display:none;" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['avatar'];?>
">
                            <img class="avatar-img"
                                 onclick="document.getElementsByClassName('avatar-input')[0].click()"
                                 src="<?php echo $_smarty_tpl->tpl_vars['user']->value['avatar'];?>
"
                                 onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
'"
                                 alt="点击修改">
                        </div>
                    </div>

                    <div>
                        <div class="form-header">QQ 号</div>
                        <div class="form-body">
                            <input type="number" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['qq'];?>
" name="qq" autocomplete="off" placeholder="请输入 QQ 号" class="layui-input">
                        </div>
                    </div>

                    <div>
                        <div class="form-header">真实姓名（提现）</div>
                        <div class="form-body">
                            <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['nicename'];?>
" name="nicename" autocomplete="off" placeholder="请输入您的真实姓名" class="layui-input">
                        </div>
                    </div>

                    <div>
                        <div class="form-header">自动结算方式</div>
                        <input type="hidden" name="settlement" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['settlement'];?>
" data-mf-settlement-input>
                        <div class="form-body pay-list mf-recharge-pay-list">
                            <a class="button-click btn-pay mf-settlement-option <?php if ($_smarty_tpl->tpl_vars['user']->value['settlement'] == 0) {?>checked<?php }?>" data-mf-settlement="0">
                                <img src="/assets/user/images/cash/alipay.png" class="pay-icon"> 支付宝
                            </a>
                            <a class="button-click btn-pay mf-settlement-option <?php if ($_smarty_tpl->tpl_vars['user']->value['settlement'] == 1) {?>checked<?php }?>" data-mf-settlement="1">
                                <img src="/assets/user/images/cash/wechat.png" class="pay-icon"> 微信
                            </a>
                            <a class="button-click btn-pay mf-settlement-option <?php if ($_smarty_tpl->tpl_vars['user']->value['settlement'] == 3) {?>checked<?php }?>" data-mf-settlement="3">
                                <img src="/assets/user/images/cash/usdt.png" class="pay-icon"> USDT(TRC20)
                            </a>
                        </div>
                    </div>

                    <div>
                        <div class="form-header">支付宝账号（提现）</div>
                        <div class="form-body">
                            <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['alipay'];?>
" name="alipay" autocomplete="off" placeholder="兑现使用的支付宝账号" class="layui-input">
                        </div>
                    </div>

                    <div>
                        <div class="form-header">钱包地址（TRC20）</div>
                        <div class="form-body">
                            <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['wallet_address'];?>
" name="wallet_address" autocomplete="off" placeholder="兑现使用的钱包地址" class="layui-input">
                        </div>
                    </div>

                    <div>
                        <div class="form-header">微信收款二维码（提现）</div>
                        <div class="form-body">
                            <input type="file" class="wechat-input" style="display:none;">
                            <input type="text" name="wechat" style="display:none;" value="">
                            <?php if ($_smarty_tpl->tpl_vars['user']->value['wechat'] == '') {?>
                                <div class="wx_qrcode_temp" onclick="document.getElementsByClassName('wechat-input')[0].click()">
                                    <i class="fa-duotone fa-regular fa-qrcode" style="font-size:34px;"></i>
                                </div>
                            <?php } else { ?>
                                <div class="wx_qrcode" onclick="document.getElementsByClassName('wechat-input')[0].click()"></div>
                            <?php }?>
                        </div>
                    </div>

                    <!--start::HOOK-->
                    <?php echo hook(\App\Consts\Hook::USER_VIEW_PERSONAL_FORM);?>

                    <!--end::HOOK-->

                    <div class="mf-page-actions mf-primary-action-bar mt-4">
                        <button type="button" class="layui-btn layui-btn-pink save-data mf-primary-action-button">
                            <i class="fa-duotone fa-regular fa-floppy-disk"></i> 保存修改
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </main>
</div>
<?php echo ready("/assets/user/controller/security/personal.js",array("_user_wechat"=>$_smarty_tpl->tpl_vars['user']->value['wechat']));?>

<?php $_smarty_tpl->_subTemplateRender("file:../Common/Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
