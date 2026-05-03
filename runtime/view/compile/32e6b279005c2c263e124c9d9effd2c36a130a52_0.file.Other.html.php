<?php
/* Smarty version 3.1.46, created on 2026-05-01 01:15:03
  from 'D:\phpstudy_pro\WWW\app\View\Admin\Config\Other.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f38e17208f37_70489052',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '32e6b279005c2c263e124c9d9effd2c36a130a52' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\Admin\\Config\\Other.html',
      1 => 1777554955,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../Header.html' => 1,
    'file:../Toolbar.html' => 1,
    'file:../Footer.html' => 1,
  ),
),false)) {
function content_69f38e17208f37_70489052 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
echo set_script_var(array("_substation_display_list"=>$_smarty_tpl->tpl_vars['config']->value['substation_display_list']));?>

<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <?php $_smarty_tpl->_subTemplateRender("file:../Toolbar.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Basic info-->
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header border-0 cursor-pointer">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">其他设置</h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--begin::Card header-->
                <!--begin::Content-->
                <div id="kt_account_profile_details" class="collapse show">
                    <!--begin::Form-->
                    <form id="data-form" class="form">
                        <!--begin::Card body-->
                        <div class="card-body p-9">

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">自定义支付回调域名(<a
                                            href="https://faka.wiki/#/zh-cn/setting-other" target="_blank" style="color: red;">详细教程</a>)</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="callback_domain"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="自定义支付回调域名，需要带http://" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['callback_domain'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label  fw-bold fs-6 required">主站域名配置(支持多域名,使用逗号分割)</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <textarea name="domain"
                                              class="form-control form-control-lg form-control-solid"
                                              placeholder="多个域名使用逗号分割"><?php echo $_smarty_tpl->tpl_vars['config']->value['domain'];?>
</textarea>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">DNS-CNAME(域名解析)</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="cname"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="CNAME域名" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['cname'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6 required">主站显示其他商家商品</label>
                                <!--begin::Label-->
                                <!--begin::Label-->
                                <div class="col-lg-8 d-flex align-items-center">
                                    <div class="form-check form-check-solid form-switch fv-row">
                                        <input class="form-check-input w-45px h-30px" type="checkbox"
                                               name="substation_display"
                                               value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['substation_display'] == 1) {?> checked <?php }?>/>
                                        <label class="form-check-label"></label>
                                    </div>
                                </div>
                                <!--begin::Label-->
                            </div>
                            <!--end::Input group-->


                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">你想要显示那些供货商到主站</label>
                                <!--begin::Label-->
                                <!--begin::Label-->
                                <div class="col-lg-8 d-flex align-items-center">
                                    <table id="substation_display_list"></table>
                                </div>
                                <!--begin::Label-->
                            </div>
                            <!--end::Input group-->


                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">单次最低充值金额</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="recharge_min"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="单次最低充值金额，0代表不限制，默认10元" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['recharge_min'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">单次最高充值金额</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="recharge_max"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="单次最高充值金额，0代表不限制" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['recharge_max'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">充值赠送</label>
                                <!--begin::Label-->
                                <!--begin::Label-->
                                <div class="col-lg-8 d-flex align-items-center">
                                    <div class="form-check form-check-solid form-switch fv-row">
                                        <input class="form-check-input w-45px h-30px" type="checkbox"
                                               name="recharge_welfare" value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['recharge_welfare'] == 1) {?>
                                        checked <?php }?>/>
                                        <label class="form-check-label"></label>
                                    </div>
                                </div>
                                <!--begin::Label-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label  fw-bold fs-6">充值赠送配置</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <textarea name="recharge_welfare_config" style="height: 100px;"
                                              class="form-control form-control-lg form-control-solid"
                                              placeholder="配置规则：假如充值100元赠送5元的情况下：
100-5
一行一个规则。"><?php echo $_smarty_tpl->tpl_vars['config']->value['recharge_welfare_config'];?>
</textarea>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label  fw-bold fs-6">客服QQ(主站)</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="service_qq"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="客服QQ" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['service_qq'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label  fw-bold fs-6">网页客服地址(主站)</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="service_url"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="网页客服地址" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['service_url'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">提现方式</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <!--begin::Options-->
                                    <div class="d-flex align-items-center mt-3">
                                        <!--begin::Option-->
                                        <label class="form-check form-check-inline form-check-solid me-5">
                                            <input class="form-check-input" name="cash_type_alipay" type="checkbox"
                                                   value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['cash_type_alipay'] == 1) {?> checked <?php }?>>
                                            <span class="fw-bold ps-2 fs-6">支付宝</span>
                                        </label>
                                        <!--end::Option-->
                                        <!--begin::Option-->
                                        <label class="form-check form-check-inline form-check-solid me-5">
                                            <input class="form-check-input" name="cash_type_wechat" type="checkbox"
                                                   value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['cash_type_wechat'] == 1) {?> checked <?php }?>>
                                            <span class="fw-bold ps-2 fs-6">微信</span>
                                        </label>
                                        <!--end::Option-->
                                        <!--begin::Option-->
                                        <label class="form-check form-check-inline form-check-solid me-5">
                                            <input class="form-check-input" name="cash_type_usdt" type="checkbox"
                                                   value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['cash_type_usdt'] == 1) {?> checked <?php }?>>
                                            <span class="fw-bold ps-2 fs-6">USDT(TRC20)</span>
                                        </label>
                                        <!--end::Option-->
                                        <!--begin::Option-->
                                        <label class="form-check form-check-inline form-check-solid me-5">
                                            <input class="form-check-input" name="cash_type_balance" type="checkbox"
                                                   value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['cash_type_balance'] == 1) {?> checked <?php }?>>
                                            <span class="fw-bold ps-2 fs-6">钱包余额</span>
                                        </label>
                                        <!--end::Option-->
                                    </div>
                                    <!--end::Options-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">提现手续费(单笔固定)</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="cash_cost"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="提现手续费" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['cash_cost'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required  fw-bold fs-6">最低提现金额</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="cash_min"
                                           class="form-control form-control-lg form-control-solid"
                                           placeholder="最低提现金额" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['cash_min'];?>
"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">默认展开分类</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <!--begin::Input-->
                                    <select name="default_category" class="form-select form-select-solid form-select-lg"
                                            data-control="select2" data-hide-search="true">
                                        <option value="0" <?php if ($_smarty_tpl->tpl_vars['config']->value['default_category'] == 0) {?> selected <?php }?>>不展开
                                        </option>
                                        <option value="recommend" <?php if ($_smarty_tpl->tpl_vars['config']->value['default_category'] == "recommend") {?> selected <?php }?>>推荐
                                        </option>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['category']->value, 'cate');
$_smarty_tpl->tpl_vars['cate']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['cate']->value) {
$_smarty_tpl->tpl_vars['cate']->do_else = false;
?>
                                            <option value="<?php echo $_smarty_tpl->tpl_vars['cate']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['config']->value['default_category'] == $_smarty_tpl->tpl_vars['cate']->value['id']) {?> selected <?php }?>><?php echo $_smarty_tpl->tpl_vars['cate']->value['name'];?>
</option>
                                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">首页商品推荐</label>
                                <!--begin::Label-->
                                <!--begin::Label-->
                                <div class="col-lg-8 d-flex align-items-center">
                                    <div class="form-check form-check-solid form-switch fv-row">
                                        <input class="form-check-input w-45px h-30px" type="checkbox"
                                               name="commodity_recommend"
                                               value="1" <?php if ($_smarty_tpl->tpl_vars['config']->value['commodity_recommend'] == 1) {?> checked <?php }?>/>
                                        <label class="form-check-label"></label>
                                    </div>
                                </div>
                                <!--begin::Label-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label  fw-bold fs-6">推荐分类名称</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <textarea class="form-control form-control-lg form-control-solid"   name="commodity_name" placeholder="首页推荐分类名称"   rows="1"><?php echo $_smarty_tpl->tpl_vars['config']->value['commodity_name'];?>
</textarea>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                        </div>
                        <!--end::Card body-->
                        <!--begin::Actions-->
                        <div class="card-footer d-flex justify-content-center py-6 px-9">
                            <button type="button" class="btn btn-primary save-data">保存设置 (Save Config)</button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Basic info-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->

<?php echo ready("/assets/admin/controller/config/other.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
