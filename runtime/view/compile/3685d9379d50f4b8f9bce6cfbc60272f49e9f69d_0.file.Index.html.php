<?php
/* Smarty version 3.1.46, created on 2026-05-01 17:13:58
  from '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/Dashboard/Index.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f46ed6ab41a0_91312360',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3685d9379d50f4b8f9bce6cfbc60272f49e9f69d' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/Dashboard/Index.html',
      1 => 1776730248,
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
function content_69f46ed6ab41a0_91312360 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Common/Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<div class="mf-page-shell">
    <?php $_smarty_tpl->_subTemplateRender("file:../Common/Nav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <main class="mf-main">

        <section class="mf-panel">
            <div class="mf-panel-heading">
                <span><i class="fa-duotone fa-regular fa-chart-line-up"></i> 我的资产</span>
            </div>
            <div class="mf-kpi-grid">
                <div class="mf-kpi-card">
                    <span>账户余额</span>
                    <strong>￥<?php echo $_smarty_tpl->tpl_vars['user']->value['balance'];?>
</strong>
                </div>
                <div class="mf-kpi-card">
                    <span>当前硬币</span>
                    <strong><?php echo $_smarty_tpl->tpl_vars['user']->value['coin'];?>
</strong>
                </div>
                <div class="mf-kpi-card">
                    <span>总充值</span>
                    <strong>￥<?php echo $_smarty_tpl->tpl_vars['user']->value['recharge'];?>
</strong>
                </div>
                <div class="mf-kpi-card">
                    <span>累计硬币收入</span>
                    <strong>￥<?php echo $_smarty_tpl->tpl_vars['user']->value['total_coin'];?>
</strong>
                </div>
                <?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel']) {?>
                    <div class="mf-kpi-card">
                        <span>今日收入</span>
                        <strong>￥<?php echo $_smarty_tpl->tpl_vars['today_income']->value;?>
</strong>
                    </div>
                    <div class="mf-kpi-card">
                        <span>昨日收入</span>
                        <strong>￥<?php echo $_smarty_tpl->tpl_vars['yesterday_income']->value;?>
</strong>
                    </div>
                    <div class="mf-kpi-card">
                        <span>本周收入</span>
                        <strong>￥<?php echo $_smarty_tpl->tpl_vars['week_income']->value;?>
</strong>
                    </div>
                    <div class="mf-kpi-card">
                        <span>总交易额</span>
                        <strong>￥<?php echo $_smarty_tpl->tpl_vars['trade']->value;?>
</strong>
                    </div>
                <?php }?>
            </div>
        </section>


        <section class="mf-panel">
            <div class="mf-panel-heading">
                <span><i class="fa-duotone fa-regular fa-link"></i> 推广返佣</span>
            </div>
            <div class="mf-info-grid">
                <dl class="mf-info-card">
                    <dt>商户密钥</dt>
                    <dd>
                        <span class="app-key"><?php echo $_smarty_tpl->tpl_vars['user']->value['app_key'];?>
</span>
                        <span class="reset-key" style="margin-left:10px;">重置</span>
                    </dd>
                </dl>


                <dl class="mf-info-card">
                    <dt>商户 ID</dt>
                    <dd><span class="mf-pill"><?php echo $_smarty_tpl->tpl_vars['user']->value['id'];?>
</span></dd>
                </dl>

                <dl class="mf-info-card">
                    <dt>推广链接</dt>
                    <dd><a class="clipboard" data-text="<?php echo $_smarty_tpl->tpl_vars['share_url']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['share_url']->value;?>
</a></dd>
                </dl>
                <dl class="mf-info-card">
                    <dt>推广人数</dt>
                    <dd><?php echo $_smarty_tpl->tpl_vars['children']->value;?>
</dd>
                </dl>
            </div>
        </section>

        <section class="mf-panel">
            <div class="mf-panel-heading">
                <span><i class="fa-duotone fa-regular fa-satellite-dish"></i> 账户情报</span>
            </div>
            <div class="mf-info-grid">
                <dl class="mf-info-card">
                    <dt>当前登录 IP</dt>
                    <dd><?php echo $_smarty_tpl->tpl_vars['user']->value['login_ip'];?>
</dd>
                </dl>
                <dl class="mf-info-card">
                    <dt>当前登录时间</dt>
                    <dd><?php echo $_smarty_tpl->tpl_vars['user']->value['login_time'];?>
</dd>
                </dl>
                <dl class="mf-info-card">
                    <dt>上次登录 IP</dt>
                    <dd><?php if ($_smarty_tpl->tpl_vars['user']->value['last_login_ip']) {
echo $_smarty_tpl->tpl_vars['user']->value['last_login_ip'];
if ($_smarty_tpl->tpl_vars['user']->value['login_ip'] != $_smarty_tpl->tpl_vars['user']->value['last_login_ip']) {?> <span class="text-danger">(异地登录)</span><?php }
} else { ?>-<?php }?></dd>
                </dl>
                <dl class="mf-info-card">
                    <dt>上次登录时间</dt>
                    <dd><?php if ($_smarty_tpl->tpl_vars['user']->value['last_login_time']) {
echo $_smarty_tpl->tpl_vars['user']->value['last_login_time'];
} else { ?>-<?php }?></dd>
                </dl>

            </div>
        </section>

    </main>
</div>
<?php echo ready("/assets/user/controller/dashboard/index.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Common/Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
