<?php
/* Smarty version 3.1.46, created on 2026-05-01 12:52:38
  from 'D:\phpstudy_pro\WWW\app\View\User\Theme\Cartoon\Index\Item.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f431965be0a3_73370761',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cf4dc2995e4029e7620536b2d8cef0f47bff089e' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\User\\Theme\\Cartoon\\Index\\Item.html',
      1 => 1777611139,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./Header.html' => 1,
    'file:./Footer.html' => 1,
  ),
),false)) {
function content_69f431965be0a3_73370761 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:./Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
echo item_var($_smarty_tpl->tpl_vars['item']->value);?>

<main class="container py-4">

    <div class="row g-4 mt-1 align-items-start">
        <!-- 左侧：宝贝详情 -->
        <div class="col-12 col-lg-8 order-2 order-lg-1">
            <div class="panel item-detail h-100">
                <div class="panel-header">
                    <span class="icon"><i class="fa-duotone fa-regular fa-memo-circle-info"></i></span>
                    <h6 class="panel-title">商品详情</h6>
                </div>
                <div class="panel-body">
                    <?php echo $_smarty_tpl->tpl_vars['item']->value['description'];?>

                </div>
            </div>
        </div>

        <!-- 右侧：下单信息 -->
        <div class="col-12 col-lg-4 order-1 order-lg-2">
            <div class="panel">
                <div class="panel-body p-4">
                    <!-- 商品标题 -->
                    <h4 class="fw-bold mb-3"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</h4>

                    <!-- 状态标签 -->
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-4">
                        <?php if ($_smarty_tpl->tpl_vars['item']->value['seckill_status'] == 1) {?>
                            <span class="badge-soft snap-up" style="display: none;"></span>
                        <?php }?>
                        <span class="badge-soft badge-soft-success"><?php if ($_smarty_tpl->tpl_vars['item']->value['delivery_way'] == 0) {?>自动发货<?php } else { ?>在线发货<?php }?></span>
                        <span class="badge-soft badge-soft-primary">已售 <?php echo $_smarty_tpl->tpl_vars['item']->value['order_sold'];?>
</span>
                        <span class="badge-soft badge-soft-success item-stock">库存 <?php echo $_smarty_tpl->tpl_vars['item']->value['stock'];?>
</span>
                    </div>

                    <!-- 价格展示 -->
                    <div class="d-flex align-items-baseline gap-2 mb-4 abacus">
                        <div class="price" style="font-size: 2rem; color: #4c58b3; font-weight: 800;">
                            <i class="fa-duotone fa-regular fa-spinner-third icon-spin fs-6"></i>
                        </div>
                    </div>

                    <!-- 下单表单 -->
                    <form class="vstack gap-3 detail-form">
                        <?php if (!empty($_smarty_tpl->tpl_vars['item']->value['config']['category'])) {?>
                            <div>
                                <label class="form-label mb-2 fw-bold">宝贝类型</label>
                                <div class="sku-list">
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['item']->value['config']['category'], 'price', false, 'race');
$_smarty_tpl->tpl_vars['price']->index = -1;
$_smarty_tpl->tpl_vars['price']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['race']->value => $_smarty_tpl->tpl_vars['price']->value) {
$_smarty_tpl->tpl_vars['price']->do_else = false;
$_smarty_tpl->tpl_vars['price']->index++;
$_smarty_tpl->tpl_vars['price']->first = !$_smarty_tpl->tpl_vars['price']->index;
$__foreach_price_0_saved = $_smarty_tpl->tpl_vars['price'];
?>
                                        <a class="switch-race sku <?php if ($_smarty_tpl->tpl_vars['price']->first) {?>is-primary<?php }?>"
                                           data-sku="<?php echo $_smarty_tpl->tpl_vars['race']->value;?>
"
                                           data-price="<?php echo $_smarty_tpl->tpl_vars['price']->value;?>
"
                                           href="javascript:void(0);"><?php echo $_smarty_tpl->tpl_vars['race']->value;?>
<span
                                                    class="badge-money">¥<?php echo $_smarty_tpl->tpl_vars['price']->value;?>
</span></a>
                                    <?php
$_smarty_tpl->tpl_vars['price'] = $__foreach_price_0_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                </div>
                            </div>
                        <?php }?>

                        <?php if (!empty($_smarty_tpl->tpl_vars['item']->value['config']['sku'])) {?>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['item']->value['config']['sku'], 'sku', false, 'name');
$_smarty_tpl->tpl_vars['sku']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['name']->value => $_smarty_tpl->tpl_vars['sku']->value) {
$_smarty_tpl->tpl_vars['sku']->do_else = false;
?>
                                <div>
                                    <label class="form-label mb-2 fw-bold"><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</label>
                                    <div class="sku-list">
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['sku']->value, 'price', false, 'key');
$_smarty_tpl->tpl_vars['price']->index = -1;
$_smarty_tpl->tpl_vars['price']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['price']->value) {
$_smarty_tpl->tpl_vars['price']->do_else = false;
$_smarty_tpl->tpl_vars['price']->index++;
$_smarty_tpl->tpl_vars['price']->first = !$_smarty_tpl->tpl_vars['price']->index;
$__foreach_price_2_saved = $_smarty_tpl->tpl_vars['price'];
?>
                                            <a class="switch-sku sku <?php if ($_smarty_tpl->tpl_vars['price']->first) {?>is-primary<?php }?>"
                                               data-sku="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
"
                                               data-value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-price="<?php echo $_smarty_tpl->tpl_vars['price']->value;?>
"
                                               href="javascript:void(0);"><?php echo $_smarty_tpl->tpl_vars['key']->value;
if ($_smarty_tpl->tpl_vars['price']->value > 0) {?><span
                                                        class="badge-money">
                                                    +¥<?php echo $_smarty_tpl->tpl_vars['price']->value;?>
</span><?php }?></a>
                                        <?php
$_smarty_tpl->tpl_vars['price'] = $__foreach_price_2_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    </div>
                                </div>
                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        <?php }?>

                        <?php if ($_smarty_tpl->tpl_vars['item']->value['draft_status'] == 1) {?>
                            <div>
                                <input type="hidden" name="card_id" class="form-control">
                                <label class="form-label mb-2 fw-bold">自选账号</label>
                                <button type="button" class="optional-card w-100 text-start">未自选,将随机发货</button>
                            </div>
                        <?php }?>

                        <?php if (!$_smarty_tpl->tpl_vars['user']->value) {?>
                            <div>
                                <label class="form-label mb-2 fw-bold"><?php echo contact_type_msg($_smarty_tpl->tpl_vars['item']->value['contact_type']);?>
</label>
                                <input type="text" name="contact" class="form-control"
                                       placeholder="请输入您的<?php echo contact_type_msg($_smarty_tpl->tpl_vars['item']->value['contact_type']);?>
">
                            </div>
                        <?php }?>

                        <?php if ($_smarty_tpl->tpl_vars['item']->value['coupon'] == 1) {?>
                            <div>
                                <label class="form-label mb-2 fw-bold">优惠券</label>
                                <input type="text" class="form-control" name="coupon"
                                       placeholder="优惠券代码，没有则不填">
                            </div>
                        <?php }?>

                        <?php echo widget_render($_smarty_tpl->tpl_vars['item']->value['widget']);?>


                        <?php if ($_smarty_tpl->tpl_vars['item']->value['password_status'] == 1) {?>
                            <div>
                                <label class="form-label mb-2 fw-bold">查询密码</label>
                                <input type="password" class="form-control" name="password"
                                       placeholder="设置查询订单的密码">
                            </div>
                        <?php }?>

                        <div>
                            <label class="form-label mb-2 fw-bold">购买数量</label>
                            <div class="item-apple-input-wrapper">
                                <input type="number" class="form-control text-center" name="num"
                                       value="<?php if ($_smarty_tpl->tpl_vars['item']->value['minimum'] > 0) {
echo $_smarty_tpl->tpl_vars['item']->value['minimum'];
} else { ?>1<?php }?>">
                            </div>
                        </div>

                        <?php if ($_smarty_tpl->tpl_vars['item']->value['trade_captcha'] == 1) {?>
                            <div>
                                <label class="form-label mb-2 fw-bold">人机验证</label>
                                <div class="item-apple-input-wrapper">
                                    <input type="text" class="form-control captcha-input" placeholder="图形验证码"
                                           name="captcha">
                                    <img class="captcha-img" src="/user/captcha/image?action=trade"/>
                                </div>
                            </div>
                        <?php }?>

                        <div class="cash-pay p-0" style="display: none;">
                            <label class="form-label mb-3 fw-bold"><i class="fa-duotone fa-regular fa-wallet me-2"></i>支付方式</label>
                            <div class="pay-list">
                                <!-- 支付列表由 JS 动态渲染 -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</main>
<?php echo ready("/assets/user/controller/index/item.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:./Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
