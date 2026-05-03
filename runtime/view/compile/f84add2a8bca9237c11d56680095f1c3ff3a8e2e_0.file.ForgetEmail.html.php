<?php
/* Smarty version 3.1.46, created on 2026-05-01 18:42:47
  from '/www/wwwroot/pcccc.cc/app/View/User/Theme/Cartoon/Authentication/ForgetEmail.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f483a76404c3_89000831',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f84add2a8bca9237c11d56680095f1c3ff3a8e2e' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Theme/Cartoon/Authentication/ForgetEmail.html',
      1 => 1777554955,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./Header.html' => 1,
    'file:./Footer.html' => 1,
  ),
),false)) {
function content_69f483a76404c3_89000831 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:./Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<!-- Main Container -->
<main class="auth-wrapper">
    <div class="auth-card">

        <div class="brand-header">
            <div class="brand-logo">
                <img src="/favicon.ico" alt="Logo" class="brand-icon">
            </div>
        </div>
        <p class="auth-subtitle small mb-3">邮箱无法接受验证码？<a class="text-link" href="/">联系客服</a>协助帮忙找回！</p>

        <form class="needs-validation">
            <div class="form-floating mb-4">
                <input type="email" class="form-control" name="username" placeholder="Email" required>
                <label for="username">Email</label>
            </div>

            <div class="row mb-4">
                <div class="col-sm-8 col-8">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="captcha"
                               name="captcha" placeholder="验证码">
                        <label class="form-label" for="captcha">验证码</label>
                    </div>
                </div>
                <div class="col-sm-4 col-4">
                    <button type="button" class="w-100 btn btn-outline-primary py-3 send-email-captcha">
                        发送验证码
                    </button>
                </div>
            </div>

            <div class="form-floating mb-4">
                <input type="text" class="form-control" name="password" placeholder="设置登录密码"
                       minlength="6" required>
                <label for="password">设置登录密码</label>
            </div>


            <div class="d-grid">
                <button type="submit" class="btn btn-gradient btn-lg">确认重置</button>
            </div>
        </form>


        <p class="text-center small mt-3 mb-0">
            想起密码？
            <a class="text-link" href="/user/authentication/login">前往登录</a>
        </p>
    </div>
</main>
<!-- END Main Container -->
<?php echo ready("/assets/user/controller/auth/forget.email.js");?>

<?php $_smarty_tpl->_subTemplateRender("file:./Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
