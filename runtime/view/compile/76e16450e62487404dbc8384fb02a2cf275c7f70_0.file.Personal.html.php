<?php
/* Smarty version 3.1.46, created on 2026-05-01 02:24:22
  from 'D:\phpstudy_pro\WWW\app\View\User\Theme\Cartoon\User\Personal.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f39e56a1dc66_56698380',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '76e16450e62487404dbc8384fb02a2cf275c7f70' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\User\\Theme\\Cartoon\\User\\Personal.html',
      1 => 1777554956,
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
function content_69f39e56a1dc66_56698380 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Common/Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<div class="layui-container fly-marginTop fly-user-main">
    <?php $_smarty_tpl->_subTemplateRender("file:../Common/Nav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <div class="fly-panel fly-panel-user" pad20>
        <div class="layui-tab layui-tab-brief">
            <?php $_smarty_tpl->_subTemplateRender("file:../Common/SecurityNav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <div class="content-body">
                <form class="layui-form form-data">
                    <div class="form-block">
                        <div>
                            <div class="form-body">
                                <input type="file" class="avatar-input" style="display: none;">
                                <input type="text" name="avatar" style="display: none;" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['avatar'];?>
">
                                <img class="avatar-img"
                                     onclick="document.getElementsByClassName('avatar-input')[0].click()"
                                     src="<?php echo $_smarty_tpl->tpl_vars['user']->value['avatar'];?>
" alt="点击修改"
                                     style="height: 100px;width: 100px;border-radius: 100%;border: 1px solid #fba9da8c;box-shadow: 1px 1px 10px 1px #ed9b9bb3;cursor: pointer;">
                            </div>
                        </div>
                        <div>
                            <div class="form-header">QQ号</div>
                            <div class="form-body"><input type="number" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['qq'];?>
" name="qq" autocomplete="off"
                                                          placeholder="请输入QQ号" class="layui-input"
                                                          style="color: #ff9191;width: 320px;"></div>
                        </div>
                        <div>
                            <div class="form-header">真实姓名(提现)</div>
                            <div class="form-body"><input type="text" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['nicename'];?>
" name="nicename"
                                                          autocomplete="off"
                                                          placeholder="请输入您的真实姓名" class="layui-input"
                                                          style="color: #ff9191;width: 320px;"></div>
                        </div>
                        <div>
                            <div class="form-header">自动结算方式</div>
                            <div class="form-body" style="color: #ff9191;width: 320px;">
                                <select name="settlement">
                                    <option value="0" <?php if ($_smarty_tpl->tpl_vars['user']->value['settlement'] == 0) {?> selected <?php }?>>支付宝</option>
                                    <option value="1" <?php if ($_smarty_tpl->tpl_vars['user']->value['settlement'] == 1) {?> selected <?php }?>>微信</option>
                                    <option value="3" <?php if ($_smarty_tpl->tpl_vars['user']->value['settlement'] == 3) {?> selected <?php }?>>USDT(TRC20)</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="form-header">支付宝账号(提现)</div>
                            <div class="form-body"><input type="text" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['alipay'];?>
" name="alipay"
                                                          autocomplete="off"
                                                          placeholder="兑现使用的支付宝账号" class="layui-input"
                                                          style="color: #ff9191;width: 320px;"></div>
                        </div>
                        <div>
                            <div class="form-header">钱包地址(TRC20)</div>
                            <div class="form-body"><input type="text" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['wallet_address'];?>
" name="wallet_address"
                                                          autocomplete="off"
                                                          placeholder="兑现使用的钱包地址" class="layui-input"
                                                          style="color: #ff9191;width: 320px;"></div>
                        </div>
                        <div>
                            <div class="form-header">微信收款二维码(提现)</div>
                            <div class="form-body">
                                <input type="file" class="wechat-input" style="display: none;">
                                <input type="text" name="wechat" style="display: none;" value="">
                                <?php if ($_smarty_tpl->tpl_vars['user']->value['wechat'] == '') {?>
                                    <i class="layui-icon wx_qrcode_temp"
                                       onclick="document.getElementsByClassName('wechat-input')[0].click()"
                                       style="font-size: 32px;color: #0C84D1;cursor: pointer;">&#xe681;</i>
                                <?php } else { ?>
                                    <div class="wx_qrcode"
                                         onclick="document.getElementsByClassName('wechat-input')[0].click()"></div>
                                <?php }?>
                            </div>
                        </div>

                        <!--start::HOOK-->
                        <?php echo hook(\App\Consts\Hook::USER_VIEW_PERSONAL_FORM);?>

                        <!--end::HOOK-->

                        <div style="margin-top: 25px;">
                            <button type="button" class="layui-btn layui-btn-pink save-data">保存修改</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php echo ready("/assets/user/controller/security/personal.js",array("_user_wechat"=>$_smarty_tpl->tpl_vars['user']->value['wechat']));?>

<?php $_smarty_tpl->_subTemplateRender("file:../Common/Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
