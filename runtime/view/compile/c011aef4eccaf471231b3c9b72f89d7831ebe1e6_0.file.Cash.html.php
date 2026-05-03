<?php
/* Smarty version 3.1.46, created on 2026-05-01 19:41:08
  from '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/User/Cash.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f491547dc819_32768779',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c011aef4eccaf471231b3c9b72f89d7831ebe1e6' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/User/Cash.html',
      1 => 1776730342,
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
function content_69f491547dc819_32768779 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Common/Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<div class="mf-page-shell mf-page-shell-cash">
    <?php $_smarty_tpl->_subTemplateRender("file:../Common/Nav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <main class="mf-main">

        <section class="mf-panel">
            <ul class="layui-tab-title">
                <li class="layui-this"><a href="/user/cash/index"><i class="fa-duotone fa-regular fa-hand-holding-dollar"></i> 硬币兑现</a></li>
                <li><a href="/user/cash/record"><i class="fa-duotone fa-regular fa-timeline-arrow"></i> 兑现记录 <span class="layui-badge"><?php echo $_smarty_tpl->tpl_vars['processing']->value;?>
</span></a></li>
            </ul>

            <blockquote class="elem-quote mf-cash-note">
                <div class="mf-cash-note-item is-balance">
                    <span class="mf-cash-note-label">
                        <i class="fa-duotone fa-regular fa-coins"></i>
                        当前拥有
                    </span>
                    <strong class="mf-cash-note-value"><?php echo $_smarty_tpl->tpl_vars['user']->value['coin'];?>
 硬币</strong>
                </div>

                <div class="mf-cash-note-item">
                    <span class="mf-cash-note-label">
                        <i class="fa-duotone fa-regular fa-badge-dollar"></i>
                        最低兑现金额
                    </span>
                    <strong class="mf-cash-note-value"><?php echo $_smarty_tpl->tpl_vars['config']->value['cash_min'];?>
 元</strong>
                </div>
                <div class="mf-cash-note-item">
                    <span class="mf-cash-note-label">
                        <i class="fa-duotone fa-regular fa-receipt"></i>
                        手动提现费用
                    </span>
                    <strong class="mf-cash-note-value"><?php echo $_smarty_tpl->tpl_vars['config']->value['cash_cost'];?>
 元</strong>
                </div>
            </blockquote>

            <form class="layui-form">
                <div class="form-block">
                    <div>
                        <div class="form-header">兑现硬币</div>
                        <div class="mf-recharge-amount-row">
                            <label class="mf-recharge-amount-field">
                                <span class="mf-recharge-currency">￥</span>
                                <input type="text"
                                       name="amount"
                                       required
                                       lay-verify="required"
                                       value="<?php echo $_smarty_tpl->tpl_vars['config']->value['cash_min'];?>
"
                                       inputmode="decimal"
                                       autocomplete="off"
                                       placeholder="输入兑现金额"
                                       class="layui-input mf-recharge-amount-input">
                            </label>
                        </div>
                    </div>
                    <div class="mf-recharge-pay-section">
                        <div class="mf-recharge-section-label">
                            <i class="fa-duotone fa-regular fa-credit-card"></i>
                            <span>兑现方式</span>
                        </div>
                        <div class="form-body pay-list mf-recharge-pay-list">
                            <?php if ($_smarty_tpl->tpl_vars['config']->value['cash_type_alipay'] == 1) {?><a class="button-click cash-wallet-btn btn-pay" data-id="0"><img src="/assets/user/images/cash/alipay.png" class="pay-icon"> 支付宝</a><?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['config']->value['cash_type_wechat'] == 1) {?><a class="button-click cash-wallet-btn btn-pay" data-id="1"><img src="/assets/user/images/cash/wechat.png" class="pay-icon"> 微信</a><?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['config']->value['cash_type_usdt'] == 1) {?><a class="button-click cash-wallet-btn btn-pay" data-id="3"><img src="/assets/user/images/cash/usdt.png" class="pay-icon"> USDT(TRC20)</a><?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['config']->value['cash_type_balance'] == 1) {?><a class="button-click cash-wallet-btn btn-pay checked" data-id="2"><img src="/assets/static/images/wallet.png" class="pay-icon"> 钱包余额</a><?php }?>
                        </div>
                    </div>
                    <div class="mf-recharge-submit mf-primary-action-bar">
                        <button type="button" class="layui-btn layui-btn-pink payButton mf-primary-action-button">
                            <i class="fa-duotone fa-regular fa-paper-plane-top"></i> 立即兑现
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </main>
</div>
<?php echo ready("/assets/user/controller/user/cash.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Common/Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
