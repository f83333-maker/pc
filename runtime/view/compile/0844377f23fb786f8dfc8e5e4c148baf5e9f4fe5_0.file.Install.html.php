<?php
/* Smarty version 3.1.46, created on 2026-05-01 17:04:00
  from '/www/wwwroot/pcccc.cc/app/View/Install.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f46c805ca938_00835537',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0844377f23fb786f8dfc8e5e4c148baf5e9f4fe5' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/Install.html',
      1 => 1777625234,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f46c805ca938_00835537 (Smarty_Internal_Template $_smarty_tpl) {
?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>系统安装程序</title>
    <?php echo '<script'; ?>
 src="/assets/static/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/assets/static/layer/layer.js"><?php echo '</script'; ?>
>

    <style>
        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f4f7f6;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            position: relative;
            top: 10%;
            background-color: #ffffff;
            -webkit-box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border-radius: 12px;
            overflow: hidden;
        }

        .wrapper {
            width: 100%;
            padding: 40px 0;
        }

        .steps {
            list-style-type: none;
            margin: 0;
            padding: 0;
            text-align: center;
            border-bottom: 1px solid #eee;
            margin-bottom: 30px;
        }

        .steps li {
            display: inline-block;
            margin: 0 15px 15px 15px;
            color: #999;
            padding-bottom: 10px;
            font-weight: 500;
        }

        .steps li.is-active {
            border-bottom: 2px solid #3498db;
            color: #3498db;
        }

        .form-wrapper .section {
            padding: 0 40px 20px 40px;
            box-sizing: border-box;
            display: none;
            text-align: center;
            width: 100%;
            min-height: 350px
        }

        .form-wrapper .section h3 {
            margin-bottom: 25px;
            color: #333;
        }

        .form-wrapper .section.is-active {
            display: block;
        }

        .form-wrapper .button, .form-wrapper .submit {
            background-color: #3498db;
            display: inline-block;
            padding: 12px 40px;
            color: #fff;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            margin-top: 25px;
            border-radius: 6px;
            transition: background 0.3s;
            border: none;
            outline: none;
        }
        
        .form-wrapper .button:hover, .form-wrapper .submit:hover {
            background-color: #2980b9;
        }

        .form-wrapper input[type="text"], .form-wrapper input[type="password"] {
            display: block;
            padding: 12px 15px;
            margin: 15px auto;
            background-color: #f9f9f9;
            border: 1px solid #eee;
            width: 85%;
            outline: none;
            font-size: 14px;
            border-radius: 6px;
        }

        .gridtable {
            width: 90%;
            margin: 0 auto 25px auto;
            border-collapse: collapse;
            font-size: 13px;
        }

        .gridtable th {
            text-align: left;
            padding: 12px;
            background-color: #f8f9fa;
            border-bottom: 2px solid #eee;
            color: #666;
        }

        .gridtable td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        .disabled {
            background-color: #ccc !important;
            cursor: not-allowed !important;
        }
        
        .success-icon {
            font-size: 60px;
            color: #2ecc71;
            margin-bottom: 20px;
            display: block;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="wrapper">
        <ul class="steps">
            <li class="is-active">环境检查</li>
            <li>数据库配置</li>
            <li>管理员设置</li>
        </ul>
        <form class="form-wrapper install-info">
            <fieldset class="section is-active">
                <h3>程序版本：<b>v<?php echo $_smarty_tpl->tpl_vars['version']->value;?>
</b></h3>
                <table class="gridtable">
                    <tr>
                        <th>依赖模块</th>
                        <th>状态</th>
                    </tr>
                    <tr>
                        <td>PHP 版本 (8.0+)</td>
                        <td><?php if ($_smarty_tpl->tpl_vars['php_version']->value >= 8) {?><span style="color: #2ecc71;">✔ 已通过</span> <?php } else { ?><span style="color: #e74c3c;">✘ 需要 8.0+ (当前: <?php echo $_smarty_tpl->tpl_vars['php_version']->value;?>
)</span><?php }?>
                        </td>
                    </tr>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ext']->value, 'val', false, 'key');
$_smarty_tpl->tpl_vars['val']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->do_else = false;
?>
                    <tr>
                        <td>PHP 扩展: <?php echo $_smarty_tpl->tpl_vars['key']->value;?>
</td>
                        <td><?php if ($_smarty_tpl->tpl_vars['val']->value) {?><span style="color: #2ecc71;">✔ 已安装</span> <?php } else { ?><span style="color: #e74c3c;">✘ 未安装</span><?php }?>
                        </td>
                    </tr>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

                </table>
                <div class="<?php if ($_smarty_tpl->tpl_vars['install']->value) {?> button <?php } else { ?> button disabled <?php }?>"><?php if ($_smarty_tpl->tpl_vars['install']->value) {?> 下一步 <?php } else { ?> 请先安装缺失扩展 <?php }?>
                </div>
            </fieldset>
            <fieldset class="section">
                <h3>数据库连接配置</h3>
                <input type="text" name="host" placeholder="数据库地址 (默认: 127.0.0.1)" value="127.0.0.1">
                <input type="text" name="database" placeholder="数据库名称">
                <input type="text" name="username" placeholder="数据库账号">
                <input type="text" name="password" placeholder="数据库密码">
                <input type="text" name="prefix" placeholder="数据表前缀 (默认: acg_)" value="acg_">
                <div class="button">下一步</div>
            </fieldset>
            <fieldset class="section">
                <h3>设置管理员账户</h3>
                <input type="text" name="email" placeholder="管理员登录邮箱">
                <input type="text" name="nickname" placeholder="管理员昵称">
                <input type="password" name="login_password" placeholder="设置登录密码">
                <input type="password" name="login_re_password" placeholder="确认登录密码">
                <input class="submit button installButton" type="button" value="开始安装">
            </fieldset>
            <fieldset class="section">
                <span class="success-icon">✔</span>
                <h3>安装成功！</h3>
                <p>程序已成功安装。</p>
                <a href="/admin" class="button">进入后台</a>
            </fieldset>
        </form>
    </div>
</div>

<?php echo '<script'; ?>
>
    $(document).ready(function () {
        $(".form-wrapper .button").click(function () {
            var button = $(this);
            if(button.hasClass('disabled')) return;
            
            var currentSection = button.parents(".section");
            var currentSectionIndex = currentSection.index();
            var headerSection = $('.steps li').eq(currentSectionIndex);

            if (currentSectionIndex == 0) {
                currentSection.removeClass("is-active").next().addClass("is-active");
                headerSection.removeClass("is-active").next().addClass("is-active");
            }

            if (currentSectionIndex == 1) {
                if ($('input[name=database]').val() == '') { layer.msg("请填写数据库名称"); return; }
                if ($('input[name=username]').val() == '') { layer.msg("请填写数据库账号"); return; }
                if ($('input[name=password]').val() == '') { layer.msg("请填写数据库密码"); return; }
                currentSection.removeClass("is-active").next().addClass("is-active");
                headerSection.removeClass("is-active").next().addClass("is-active");
            }

            if (currentSectionIndex == 2) {
                if ($('input[name=email]').val() == '') { layer.msg("请设置管理员邮箱"); return; }
                if ($('input[name=nickname]').val() == '') { layer.msg("昵称不能为空"); return; }
                if ($('input[name=login_password]').val() == '') { layer.msg("请设置登录密码"); return; }
                if ($('input[name=login_password]').val() != $('input[name=login_re_password]').val()) { layer.msg("两次密码输入不一致"); return; }

                let installButton = $('.installButton');
                installButton.addClass('disabled').val('正在安装...');

                $.post('/install/submit', $('.install-info').serialize(), res => {
                    if (res.code == 200) {
                        currentSection.removeClass("is-active").next().addClass("is-active");
                        headerSection.removeClass("is-active").next().addClass("is-active");
                    } else {
                        layer.msg(res.msg);
                        installButton.removeClass('disabled').val('重新安装');
                    }
                });
            }
        });
    });
<?php echo '</script'; ?>
>
</body>
</html>
<?php }
}
