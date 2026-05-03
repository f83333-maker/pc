<?php
/* Smarty version 3.1.46, created on 2026-05-01 17:13:58
  from '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/Common/Nav.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f46ed6ace251_78193866',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '29606dcc39fcbb4b63a1f18aa82fc9b86795b1c5' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Theme/MountFuji/Common/Nav.html',
      1 => 1776731158,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f46ed6ace251_78193866 (Smarty_Internal_Template $_smarty_tpl) {
?><aside class="mf-sidebar">
    <div class="mf-sidebar-card">
        <div class="mf-sidebar-profile">
            <div class="mf-user-chip" style="justify-content:flex-start;padding:0;border:0;background:none;">
                <img src="<?php echo $_smarty_tpl->tpl_vars['user']->value['avatar'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['user']->value['username'];?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
'">
                <span class="mf-user-meta">
                    <strong><?php echo $_smarty_tpl->tpl_vars['user']->value['username'];?>
</strong>
                    <span>UID <?php echo $_smarty_tpl->tpl_vars['user']->value['id'];?>
</span>
                </span>
            </div>
            <div class="mf-sidebar-badges">
                <span class="mf-pill"><i class="fa-duotone fa-regular fa-wallet"></i> 余额 ￥<?php echo $_smarty_tpl->tpl_vars['user']->value['balance'];?>
</span>
                <span class="mf-pill"><i class="fa-duotone fa-regular fa-coins"></i> 硬币 <?php echo $_smarty_tpl->tpl_vars['user']->value['coin'];?>
</span>
            </div>
        </div>
    </div>

    <nav class="mf-sidebar-card">
        <div class="mf-side-nav layui-nav-tree">
            <div class="mf-nav-group">
                <div class="mf-nav-group-label">概览</div>
                <ul class="mf-side-nav">
                    <li class="mf-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '个人主页') {?>is-active<?php }?>">
                        <a href="/user/dashboard/index">
                            <i class="fa-duotone fa-regular fa-compass-drafting"></i>
                            <span class="mf-nav-copy">
                                <strong>控制台</strong>
                            </span>
                        </a>
                    </li>
                    <li class="mf-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '充值中心') {?>is-active<?php }?>">
                        <a href="/user/recharge/index">
                            <i class="fa-duotone fa-regular fa-circle-dollar"></i>
                            <span class="mf-nav-copy">
                                <strong>充值中心</strong>
                            </span>
                        </a>
                    </li>
                    <li class="mf-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '购买记录') {?>is-active<?php }?>">
                        <a href="/user/personal/purchaseRecord">
                            <i class="fa-duotone fa-regular fa-receipt"></i>
                            <span class="mf-nav-copy">
                                <strong>购买记录</strong>
                            </span>
                        </a>
                    </li>
                    <li class="mf-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '我的账单') {?>is-active<?php }?>">
                        <a href="/user/bill/index">
                            <i class="fa-duotone fa-regular fa-scroll"></i>
                            <span class="mf-nav-copy">
                                <strong>我的账单</strong>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="mf-nav-group">
                <div class="mf-nav-group-label">经营</div>
                <ul class="mf-side-nav">
                    <li class="mf-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '我的店铺') {?>is-active<?php }?>">
                        <a href="/user/business/index">
                            <i class="fa-duotone fa-regular fa-store"></i>
                            <span class="mf-nav-copy">
                                <strong>我的店铺</strong>
                            </span>
                        </a>
                    </li>
                    <?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel'] && $_smarty_tpl->tpl_vars['user']->value['businessLevel']['supplier'] == 1) {?>
                        <li class="mf-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '商品分类') {?>is-active<?php }?>">
                            <a href="/user/category/index">
                                <i class="fa-duotone fa-regular fa-layer-group"></i>
                                <span class="mf-nav-copy">
                                    <strong>商品分类</strong>
                                </span>
                            </a>
                        </li>
                        <li class="mf-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '我的商品') {?>is-active<?php }?>">
                            <a href="/user/commodity/index">
                                <i class="fa-duotone fa-regular fa-box-open-full"></i>
                                <span class="mf-nav-copy">
                                    <strong>我的商品</strong>
                                </span>
                            </a>
                        </li>
                        <li class="mf-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '卡密管理') {?>is-active<?php }?>">
                            <a href="/user/card/index">
                                <i class="fa-duotone fa-regular fa-key-skeleton"></i>
                                <span class="mf-nav-copy">
                                    <strong>卡密管理</strong>
                                </span>
                            </a>
                        </li>
                        <li class="mf-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '代卷管理') {?>is-active<?php }?>">
                            <a href="/user/coupon/index">
                                <i class="fa-duotone fa-regular fa-ticket-perforated"></i>
                                <span class="mf-nav-copy">
                                    <strong>代券管理</strong>
                                </span>
                            </a>
                        </li>
                        <li class="mf-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '商品订单') {?>is-active<?php }?>">
                            <a href="/user/order/index">
                                <i class="fa-duotone fa-regular fa-truck-fast"></i>
                                <span class="mf-nav-copy">
                                    <strong>商品订单</strong>
                                </span>
                            </a>
                        </li>
                    <?php }?>
                </ul>
            </div>

            <div class="mf-nav-group">
                <div class="mf-nav-group-label">账户</div>
                <ul class="mf-side-nav">
                    <li class="mf-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '硬币兑现' || $_smarty_tpl->tpl_vars['title']->value == '兑现记录') {?>is-active<?php }?>">
                        <a href="/user/cash/index">
                            <i class="fa-duotone fa-regular fa-hand-holding-dollar"></i>
                            <span class="mf-nav-copy">
                                <strong>硬币兑现</strong>
                            </span>
                        </a>
                    </li>
                    <li class="mf-nav-item <?php if ($_smarty_tpl->tpl_vars['title']->value == '我的下级') {?>is-active<?php }?>">
                        <a href="/user/agent/member">
                            <i class="fa-duotone fa-regular fa-users-viewfinder"></i>
                            <span class="mf-nav-copy">
                                <strong>我的下级</strong>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            <!--start::HOOK-->
            <?php echo hook(\App\Consts\Hook::USER_VIEW_MENU);?>

            <!--end::HOOK-->
        </div>
    </nav>
</aside>
<?php }
}
