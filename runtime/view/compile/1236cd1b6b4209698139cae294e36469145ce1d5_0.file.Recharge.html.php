<?php
/* Smarty version 3.1.46, created on 2026-05-01 21:27:17
  from '/www/wwwroot/pcccc.cc/app/View/User/Theme/Cartoon/User/Recharge.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f4aa35aa2497_86645598',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1236cd1b6b4209698139cae294e36469145ce1d5' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Theme/Cartoon/User/Recharge.html',
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
function content_69f4aa35aa2497_86645598 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Common/Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<div class="layui-container fly-marginTop fly-user-main">
    <?php $_smarty_tpl->_subTemplateRender("file:../Common/Nav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <div class="fly-panel fly-panel-user" pad20>
        <div class="layui-tab layui-tab-brief" lay-filter="user">
            <div class="content-header">
                <i class="layui-icon">&#xe638;</i> 充值余额
            </div>

            <div class="content-body">
                <blockquote class="elem-quote">
                    当前元气：<b style="color: #0C84D1;"><?php echo $_smarty_tpl->tpl_vars['user']->value['recharge'];?>
</b>，您当前用户等级：<b
                            style="color: #0C84D1;"><?php echo $_smarty_tpl->tpl_vars['user']->value['group']['name'];?>
</b>
                    <?php if ($_smarty_tpl->tpl_vars['groupNext']->value) {?>，升级到
                        <b style="color: #8d16e3;"><?php echo $_smarty_tpl->tpl_vars['groupNext']->value['name'];?>
</b>
                        还需要充值
                        <b style="color: #8d16e3;">¥<?php echo $_smarty_tpl->tpl_vars['groupNext']->value['recharge']-$_smarty_tpl->tpl_vars['user']->value['recharge'];?>
</b>
                    <?php }?>
                </blockquote>

                <form class="layui-form" action="">
                    <div class="form-block">
                        <?php if ($_smarty_tpl->tpl_vars['config']->value['recharge_welfare'] == 1 && count($_smarty_tpl->tpl_vars['welfareConfig']->value) > 0) {?>
                            <div style="margin-top: 25px;">
                                <div class="form-body">
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['welfareConfig']->value, 'wc');
$_smarty_tpl->tpl_vars['wc']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['wc']->value) {
$_smarty_tpl->tpl_vars['wc']->do_else = false;
?>
                                        <p style="color: #108d25;padding-bottom: 5px;">一次性充值<b
                                                    style="color: #f15858;">￥<?php echo $_smarty_tpl->tpl_vars['wc']->value['recharge'];?>
</b>，赠送<b
                                                    style="color: #6a71f1;">￥<?php echo $_smarty_tpl->tpl_vars['wc']->value['amount'];?>
</b></p>
                                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                </div>
                            </div>
                        <?php }?>
                        <div>
                            <div class="form-header">金额 <span
                                        style="color: #63b584;font-size: 14px;">当前余额:￥<?php echo $_smarty_tpl->tpl_vars['user']->value['balance'];?>
</span></div>
                            <div class="form-body"><input type="text" name="amount" required lay-verify="required"
                                                          value="<?php if ($_smarty_tpl->tpl_vars['config']->value['recharge_min'] == 0) {?>10<?php } else {
echo $_smarty_tpl->tpl_vars['config']->value['recharge_min'];
}?>" autocomplete="off" class="layui-input"
                                                          style="width: 150px;color: #ff9191;"></div>
                        </div>
                        <div>
                            <div class="form-header">支付方式</div>
                            <div class="form-body pay-list"></div>
                        </div>

                        <div style="margin-top: 25px;">
                            <button type="button" class="layui-btn layui-btn-pink payButton">立即充值</button>
                        </div>

                        <div>
                            <div class="form-header">会员等级划分（<span style="color: #0a53be;">说明：充值/消费可获得元气，比例1:1</span>）</div>
                            <div class="form-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td>等级</td>
                                        <td>所需元气</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['groups']->value, 'g');
$_smarty_tpl->tpl_vars['g']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['g']->value) {
$_smarty_tpl->tpl_vars['g']->do_else = false;
?>
                                        <tr>
                                            <td><img src="<?php echo $_smarty_tpl->tpl_vars['g']->value['icon'];?>
" style="height: 32px;"> <span class="layui-badge layui-bg-gray"><?php echo $_smarty_tpl->tpl_vars['g']->value['name'];?>
</span></td>
                                            <td style="color: #f68b8b;font-weight: bolder;"><?php echo $_smarty_tpl->tpl_vars['g']->value['recharge'];?>
</td>
                                        </tr>
                                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php echo ready("/assets/user/controller/user/recharge.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Common/Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
