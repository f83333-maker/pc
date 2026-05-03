<?php
/* Smarty version 3.1.46, created on 2026-05-01 02:38:55
  from 'D:\phpstudy_pro\WWW\app\View\User\Theme\Cartoon\User\Password.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f3a1bf235739_92102905',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4f5d56a51f33ef66917ef63b5e03ced8cfe9b1b1' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\User\\Theme\\Cartoon\\User\\Password.html',
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
function content_69f3a1bf235739_92102905 (Smarty_Internal_Template $_smarty_tpl) {
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
                            <div class="form-header">旧密码</div>
                            <div class="form-body"><input type="password" name="old_password" autocomplete="off"
                                                          class="layui-input" placeholder="旧密码"
                                                          style="color: #ff9191;width: 320px;"></div>
                        </div>

                        <div>
                            <div class="form-header">新密码</div>
                            <div class="form-body"><input type="password" name="password" autocomplete="off"
                                                          class="layui-input" placeholder="新密码"
                                                          style="color: #ff9191;width: 320px;"></div>
                        </div>

                        <div>
                            <div class="form-header">确认新密码</div>
                            <div class="form-body"><input type="password" name="re_password" autocomplete="off"
                                                          class="layui-input" placeholder="确认新密码"
                                                          style="color: #ff9191;width: 320px;"></div>
                        </div>


                        <div style="margin-top: 25px;">
                            <button type="button" class="layui-btn layui-btn-pink save-data">保存修改</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php echo ready("/assets/user/controller/security/password.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:../Common/Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
