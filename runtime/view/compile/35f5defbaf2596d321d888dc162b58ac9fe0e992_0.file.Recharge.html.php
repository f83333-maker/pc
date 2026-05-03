<?php
/* Smarty version 3.1.46, created on 2026-05-03 14:15:52
  from '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/User/Recharge.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f6e8183a0328_85986143',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '35f5defbaf2596d321d888dc162b58ac9fe0e992' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/User/Recharge.html',
      1 => 1777788812,
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
function content_69f6e8183a0328_85986143 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Common/Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<div class="mf-page-shell mf-page-shell-recharge" data-mf-balance="<?php echo $_smarty_tpl->tpl_vars['user']->value['balance'];?>
">
    <?php $_smarty_tpl->_subTemplateRender("file:../Common/Nav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <main class="mf-main">
        <section class="mf-panel mf-recharge-core">
            <div class="mf-panel-heading">
                <span><i class="fa-duotone fa-regular fa-circle-dollar"></i> 充值中心</span>
            </div>

            <div class="mf-recharge-core-grid">
                <section class="mf-recharge-block mf-recharge-summary-block"
                         data-mf-progress-current="<?php echo $_smarty_tpl->tpl_vars['user']->value['recharge'];?>
"
                         data-mf-progress-target="<?php if ($_smarty_tpl->tpl_vars['groupNext']->value) {
echo $_smarty_tpl->tpl_vars['groupNext']->value['recharge'];
} else {
echo $_smarty_tpl->tpl_vars['user']->value['recharge'];
}?>">
                    <div class="mf-recharge-summary-grid">
                        <article class="mf-recharge-summary-item">
                            <span>钱包余额</span>
                            <strong>￥<?php echo $_smarty_tpl->tpl_vars['user']->value['balance'];?>
</strong>
                        </article>
                        <article class="mf-recharge-summary-item">
                            <span>累计元气</span>
                            <strong><?php echo $_smarty_tpl->tpl_vars['user']->value['recharge'];?>
</strong>
                        </article>
                        <article class="mf-recharge-summary-item">
                            <span>当前等级</span>
                            <strong><?php echo $_smarty_tpl->tpl_vars['user']->value['group']['name'];?>
</strong>
                        </article>
                        <article class="mf-recharge-summary-item">
                            <span><?php if ($_smarty_tpl->tpl_vars['groupNext']->value) {?>下一等级<?php } else { ?>当前状态<?php }?></span>
                            <strong><?php if ($_smarty_tpl->tpl_vars['groupNext']->value) {
echo $_smarty_tpl->tpl_vars['groupNext']->value['name'];
} else { ?>已满级<?php }?></strong>
                        </article>
                    </div>

                    <div class="mf-recharge-summary-progress">
                        <div class="mf-recharge-progress-track">
                            <span data-mf-progress-bar></span>
                        </div>
                        <p>
                            <?php if ($_smarty_tpl->tpl_vars['groupNext']->value) {?>
                                距离 <?php echo $_smarty_tpl->tpl_vars['groupNext']->value['name'];?>
 还差 ￥<?php echo $_smarty_tpl->tpl_vars['groupNext']->value['recharge']-$_smarty_tpl->tpl_vars['user']->value['recharge'];?>

                            <?php } else { ?>
                                已达到当前最高等级
                            <?php }?>
                        </p>
                    </div>
                </section>

                <form class="layui-form mf-recharge-block mf-recharge-action-block">
                    <div class="mf-recharge-block-head mf-recharge-action-head">
                        <div>
                            <strong>充值金额</strong>
                        </div>
                    </div>

                    <div class="mf-recharge-amount-row">
                        <label class="mf-recharge-amount-field">
                            <span class="mf-recharge-currency">￥</span>
                            <input type="text"
                                   name="amount"
                                   data-mf-recharge-input
                                   required
                                   lay-verify="required"
                                   value="<?php if ($_smarty_tpl->tpl_vars['config']->value['recharge_min'] == 0) {?>10<?php } else {
echo $_smarty_tpl->tpl_vars['config']->value['recharge_min'];
}?>"
                                   inputmode="decimal"
                                   autocomplete="off"
                                   placeholder="输入充值金额"
                                   class="layui-input mf-recharge-amount-input">
                        </label>
                    </div>

                    <div class="mf-recharge-pay-section">
                        <div class="mf-recharge-section-label">
                            <i class="fa-duotone fa-regular fa-credit-card"></i>
                            <span>支付方式</span>
                        </div>
                        <div class="form-body pay-list mf-recharge-pay-list"></div>
                    </div>

                    <div class="mf-recharge-submit">
                        <button type="button" class="layui-btn layui-btn-pink payButton">
                            <i class="fa-duotone fa-regular fa-bolt"></i> 立即充值
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section class="mf-panel mf-recharge-level-panel">
            <div class="mf-panel-heading">
                <span><i class="fa-duotone fa-regular fa-crown"></i> 会员等级</span>
            </div>
            <div class="mf-recharge-level-list">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['groups']->value, 'g');
$_smarty_tpl->tpl_vars['g']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['g']->value) {
$_smarty_tpl->tpl_vars['g']->do_else = false;
?>
                    <article class="mf-recharge-level-item <?php if ($_smarty_tpl->tpl_vars['g']->value['name'] == $_smarty_tpl->tpl_vars['user']->value['group']['name']) {?>is-current<?php }?> <?php if ($_smarty_tpl->tpl_vars['groupNext']->value && $_smarty_tpl->tpl_vars['g']->value['name'] == $_smarty_tpl->tpl_vars['groupNext']->value['name']) {?>is-next<?php }?>">
                        <div class="mf-recharge-level-left">
                            <img src="<?php echo $_smarty_tpl->tpl_vars['g']->value['icon'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['g']->value['name'];?>
" loading="lazy" decoding="async">
                            <div>
                                <strong><?php echo $_smarty_tpl->tpl_vars['g']->value['name'];?>
</strong>
                                <span>
                                    <?php if ($_smarty_tpl->tpl_vars['g']->value['name'] == $_smarty_tpl->tpl_vars['user']->value['group']['name']) {?>
                                        当前等级
                                    <?php } elseif ($_smarty_tpl->tpl_vars['groupNext']->value && $_smarty_tpl->tpl_vars['g']->value['name'] == $_smarty_tpl->tpl_vars['groupNext']->value['name']) {?>
                                        下一目标
                                    <?php } elseif ($_smarty_tpl->tpl_vars['user']->value['recharge'] >= $_smarty_tpl->tpl_vars['g']->value['recharge']) {?>
                                        已解锁
                                    <?php } else { ?>
                                        未达成
                                    <?php }?>
                                </span>
                            </div>
                        </div>
                        <div class="mf-recharge-level-right">
                            <span>所需元气</span>
                            <strong><?php echo $_smarty_tpl->tpl_vars['g']->value['recharge'];?>
</strong>
                        </div>
                    </article>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </div>
        </section>
    </main>
</div>
<?php echo ready("/assets/user/controller/user/recharge.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Common/Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
