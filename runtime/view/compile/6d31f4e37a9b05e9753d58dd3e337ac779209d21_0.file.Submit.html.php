<?php
/* Smarty version 3.1.46, created on 2026-05-02 22:01:43
  from '/www/wwwroot/pcccc.cc/app/View/User/Submit.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f603c735aa29_07380668',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6d31f4e37a9b05e9753d58dd3e337ac779209d21' => 
    array (
      0 => '/www/wwwroot/pcccc.cc/app/View/User/Submit.html',
      1 => 1777554955,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f603c735aa29_07380668 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="UTF-8">
    <title>请稍等,正在发起请求..</title>
</head>
<body>
请稍等,正在为您下单...
<form action="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
" method="post" id="request">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value, 'val', false, 'key');
$_smarty_tpl->tpl_vars['val']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->do_else = false;
?>
    <input type="text" name="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['val']->value;?>
" hidden>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</form>
<?php echo '<script'; ?>
>
    document.getElementById("request").submit();
<?php echo '</script'; ?>
>
</body>
</html><?php }
}
