<?php
/* Smarty version 3.1.46, created on 2026-05-01 21:27:16
  from '/www/wwwroot/pcccc.cc/app/View/User/Theme/Cartoon/User/Business.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f4aa340d6924_62850204',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a7549dc18b311943832fe5fa0e2c9de4d5c809f9' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Theme/Cartoon/User/Business.html',
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
function content_69f4aa340d6924_62850204 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Common/Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
echo set_script_var(array("_business_notice_var"=>$_smarty_tpl->tpl_vars['business']->value['notice']));?>

<div class="layui-container fly-marginTop fly-user-main">
    <?php $_smarty_tpl->_subTemplateRender("file:../Common/Nav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <div class="fly-panel fly-panel-user" pad20>
        <div class="layui-tab layui-tab-brief" lay-filter="user">
            <div class="content-header">
                <i class="layui-icon">&#xe638;</i> 我的店铺
            </div>

            <div class="content-body">
                <?php if (!$_smarty_tpl->tpl_vars['business']->value) {?>
                    <blockquote class="elem-quote">
                        您还没有开通店铺
                    </blockquote>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['purchase']->value == 1) {?>
                    <blockquote class="elem-quote">
                        升级您的店铺，享受更多权益
                    </blockquote>
                <?php }?>
                <?php if (!$_smarty_tpl->tpl_vars['business']->value || $_smarty_tpl->tpl_vars['purchase']->value == 1 && $_smarty_tpl->tpl_vars['business']->value) {?>
                    <form class="layui-form" action="">
                        <div class="form-block">
                            <div>
                                <div class="form-header">请选择</div>
                                <div class="form-body">
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['level']->value, 'le');
$_smarty_tpl->tpl_vars['le']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['le']->value) {
$_smarty_tpl->tpl_vars['le']->do_else = false;
?>
                                        <a class="button-click business-group" data-id="<?php echo $_smarty_tpl->tpl_vars['le']->value['id'];?>
"
                                           style="line-height: 22px;color: #db66ac;"> <img
                                                    src="<?php echo $_smarty_tpl->tpl_vars['le']->value['icon'];?>
" class="pay-icon" style="margin-top: -5px;"> <span
                                                    style="font-weight: bold;"><?php echo $_smarty_tpl->tpl_vars['le']->value['name'];?>
</span> <span
                                                    style="font-weight: bold;">¥<?php echo $_smarty_tpl->tpl_vars['le']->value['price'];?>
</span>
                                            <hr>
                                            <div style="font-size: 12px;">
                                                <p>分站功能：<?php if ($_smarty_tpl->tpl_vars['le']->value['substation'] == 1) {?><i class="layui-icon"
                                                                                    style="color: green;">&#xe605;</i>
                                                    <?php } else { ?><i class="layui-icon" style="color: red;">&#x1006;</i><?php }?>
                                                </p>
                                                <p>分站返佣：<i class="layui-icon" style="color: green;">&#xe605;</i></p>
                                                <p>独立域名：<?php if ($_smarty_tpl->tpl_vars['le']->value['top_domain'] == 1) {?><i class="layui-icon"
                                                                                    style="color: green;">&#xe605;</i>
                                                    <?php } else { ?><i class="layui-icon" style="color: red;">&#x1006;</i><?php }?>
                                                </p>
                                                <p>供货权限：<?php if ($_smarty_tpl->tpl_vars['le']->value['supplier'] == 1) {?><i class="layui-icon"
                                                                                  style="color: green;">&#xe605;</i>
                                                    <?php } else { ?><i class="layui-icon" style="color: red;">&#x1006;</i><?php }?>
                                                </p>
                                                <p>供货手续费：<?php if ($_smarty_tpl->tpl_vars['le']->value['supplier'] == 1) {
echo $_smarty_tpl->tpl_vars['le']->value['cost']*100;?>
%<?php } else { ?>-<?php }?></p>
                                            </div>
                                        </a>
                                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                </div>
                            </div>
                            <div style="margin-top: 10px;">
                                <blockquote class="elem-tips" style="font-size: 12px;">
                                    <p>• 分站返佣：开通分站后，您在分站售出的主站商品，将按差价返还佣金（实际成交金额 - 您的拿货价 = 您的佣金）。</p>
                                    <p>• 独立域名：开通分站后，您可绑定自己的顶级域名，而无需使用系统默认分配的子域名。</p>
                                    <p>• 供货权限：您可自建商品分类并上架商品进行销售，主站也将协助推广与售卖。</p>
                                    <p>• 供货手续费：针对您自主上架的商品，每笔成功交易将收取一定比例的手续费。</p>
                                </blockquote>

                            </div>
                            <div style="margin-top: 25px;">
                                <button type="button" class="layui-btn layui-btn-pink payButton"><?php if (!$_smarty_tpl->tpl_vars['business']->value) {?>立即开通
                                    <?php }
if ($_smarty_tpl->tpl_vars['purchase']->value == 1) {?>立即升级<?php }?>
                                </button>
                                <?php if ($_smarty_tpl->tpl_vars['purchase']->value == 1) {?>
                                    <a href="/user/business/index" class="layui-btn layui-btn-pink">返回店铺</a>
                                <?php }?>
                            </div>
                        </div>
                    </form>
                <?php } else { ?>
                    <?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel']['substation'] == 1) {?>
                        <blockquote class="elem-quote">
                            当前商户等级：<img src="<?php echo $_smarty_tpl->tpl_vars['user']->value['businessLevel']['icon'];?>
"
                                        style="height: 16px;margin-top: -4px;"><?php echo $_smarty_tpl->tpl_vars['user']->value['businessLevel']['name'];?>
，绑定独立域名：<?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel']['top_domain'] == 1) {?><b style="color: green;">✔</b><?php } else { ?><b
                                style="color: red;">✖</b><?php }?>，供货权限：<?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel']['supplier'] == 1) {?><b
                                style="color: green;">✔</b>，供货手续费：<?php echo $_smarty_tpl->tpl_vars['user']->value['businessLevel']['cost']*100;?>
%<?php } else { ?><b
                                style="color: red;">✖</b><?php }?> <?php if (count($_smarty_tpl->tpl_vars['level']->value) > 0) {?>
                                <a href="/user/business/index?purchase=1"
                                   style="color: #a372d7;font-weight: bold;margin-left: 10px;">我要升级，变得更强！</a>
                            <?php }?>
                        </blockquote>
                        <form class="layui-form form-data">
                            <div>
                                <div class="form-header">店铺名称</div>
                                <div class="form-body"><input type="text" value="<?php echo $_smarty_tpl->tpl_vars['business']->value['shop_name'];?>
"
                                                              name="shop_name"
                                                              required autocomplete="off" placeholder="请输入店铺名称"
                                                              class="layui-input" style="color: #ff9191;"></div>
                            </div>
                            <div>
                                <div class="form-header">网站标题</div>
                                <div class="form-body"><input type="text" value="<?php echo $_smarty_tpl->tpl_vars['business']->value['title'];?>
" name="title"
                                                              required
                                                              autocomplete="off" placeholder="用于显示浏览器标题"
                                                              class="layui-input"
                                                              style="color: #ff9191;"></div>
                            </div>
                            <div>
                                <div class="form-header">店铺公告</div>
                                <div class="form-body">

                                    <div class="editor-wrapper">
                                        <div>
                                            <button data-type="0" class="button-switch-notice" type="button"
                                                    style="width: 100%;border: none;background: rgba(255, 255, 255, 0.35);border-radius: 5px 5px 0 0;color: #c9b8b8;">
                                                <i class="fa-duotone fa-regular fa-code me-1"></i>HTML
                                            </button>
                                        </div>
                                        <div class="editor-content">
                                            <div class="toolbar-container"></div>
                                            <div class="editor-container"></div>
                                        </div>
                                        <textarea class="text-container" style="display: none;"
                                                  name="notice"></textarea>
                                    </div>

                                </div>
                            </div>
                            <div>
                                <div class="form-header">显示主站商品[全局]</div>
                                <div class="form-body"><input type="checkbox" name="master_display" title="开启" value="1"
                                                              <?php if ($_smarty_tpl->tpl_vars['business']->value['master_display'] == 1) {?>checked<?php }?>>
                                </div>
                            </div>

                            <div>
                                <div class="form-header">主站分类
                                    <span class="a-badge a-badge-success category-show" data-state="1"
                                          style="cursor:pointer;">全部显示</span>
                                    <span class="a-badge a-badge-danger category-show" data-state="0"
                                          style="cursor:pointer;">全部隐藏</span>
                                </div>
                                <div class="form-body">
                                    <table id="master_category" lay-filter="master_category"></table>
                                </div>
                            </div>

                            <div>
                                <div class="form-header">主站商品
                                    <a id="commodity"></a>
                                    <span class="a-badge a-badge-success commodity-show" data-state="1"
                                          style="cursor:pointer;">全部显示</span>
                                    <span class="a-badge a-badge-danger commodity-show" data-state="0"
                                          style="cursor:pointer;">全部隐藏</span>

                                    <span class="a-badge a-badge-primary commodity-premium"
                                          style="cursor:pointer;">加价</span>
                                </div>
                                <div class="form-body">
                                    <table id="master_commodity" lay-filter="master_commodity"></table>
                                </div>
                            </div>
                            
                            <div>
                                <div class="form-header">子域名 <?php if ($_smarty_tpl->tpl_vars['business']->value['subdomain']) {?>
                                        <i class="fa-duotone fa-regular fa-check text-success"></i>
                                        <a href="javascript:void(0);" class="unbind-subdomain text-success">解绑</a>
                                    <?php }?>
                                </div>
                                <div class="form-body">
                                    <div class="layui-row" style="width: 400px;">
                                        <div class="layui-col-md5">
                                            <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['business']->value['subdomain'];?>
" name="subdomain" required
                                                   autocomplete="off" placeholder="前缀" class="layui-input"
                                                   style="color: #ff9191;" <?php if ($_smarty_tpl->tpl_vars['business']->value['subdomain']) {?> disabled <?php }?>>
                                        </div>
                                        <div class="layui-col-md7">
                                            <select class="layui-select "
                                                    name="suffix" <?php if ($_smarty_tpl->tpl_vars['business']->value['subdomain']) {?> disabled <?php }?>>
                                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['domain']->value, 'd');
$_smarty_tpl->tpl_vars['d']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['d']->value) {
$_smarty_tpl->tpl_vars['d']->do_else = false;
?>
                                                    <option value="<?php echo $_smarty_tpl->tpl_vars['d']->value;?>
">.<?php echo $_smarty_tpl->tpl_vars['d']->value;?>
</option>
                                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($_smarty_tpl->tpl_vars['user']->value['businessLevel']['top_domain'] == 1) {?>
                                    <div>
                                        <div class="form-header">独立域名 <?php if ($_smarty_tpl->tpl_vars['business']->value['topdomain']) {?>
                                                <a href="javascript:void(0);" class="unbind-topdomain text-success">解绑</a>
                                                [<?php if (gethostbyname($_smarty_tpl->tpl_vars['config']->value['cname']) == gethostbyname($_smarty_tpl->tpl_vars['business']->value['topdomain'])) {?>
                                                    <b style="color: green;">正常</b>
                                                <?php } else { ?>
                                                    <b style="color: mediumvioletred;">未检测到解析</b>
                                                <?php }?>] <?php }?> <span class="text-danger">CNAME解析地址：<b
                                                        style="color: green;"><?php echo $_smarty_tpl->tpl_vars['config']->value['cname'];?>
</b></span></div>
                                        <div class="form-body"><input type="text" value="<?php echo $_smarty_tpl->tpl_vars['business']->value['topdomain'];?>
"
                                                                      name="topdomain"
                                                                      required autocomplete="off" placeholder="绑定独立域名"
                                                                      class="layui-input"
                                                                      style="width:260px;color: #ff9191;" <?php if ($_smarty_tpl->tpl_vars['business']->value['topdomain']) {?> disabled <?php }?>>
                                        </div>
                                    </div>
                                <?php }?>
                                <div style="margin-top: 25px;">
                                    <button type="button" class="layui-btn layui-btn-pink save-config me-1">保存设置</button>
                                </div>
                        </form>
                    <?php } else { ?>
                        <blockquote class="elem-quote">
                            您当前商户等级暂不支持店铺功能，<a href="/user/business/index?purchase=1">立即升级</a>
                        </blockquote>
                    <?php }?>
                <?php }?>
            </div>
        </div>
    </div>
</div>

<?php echo ready("/assets/user/controller/business/business.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Common/Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
