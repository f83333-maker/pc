<?php
/* Smarty version 3.1.46, created on 2026-05-01 15:26:57
  from 'D:\phpstudy_pro\WWW\app\View\404.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69f455c1996321_16608906',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '643f5549f535d12157b9726609dc01fdb48bf13b' => 
    array (
      0 => 'D:\\phpstudy_pro\\WWW\\app\\View\\404.html',
      1 => 1777618413,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69f455c1996321_16608906 (Smarty_Internal_Template $_smarty_tpl) {
?><!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
</title>
  <style>
    body {
      background: radial-gradient(at 0% 0%, #2fa395 0%, transparent 70%), 
                  radial-gradient(at 100% 0%, #1565c0 0%, transparent 70%), 
                  radial-gradient(at 50% 50%, #c2185b 0%, transparent 80%),
                  radial-gradient(at 0% 100%, #004d40 0%, transparent 70%), 
                  radial-gradient(at 100% 100%, #283593 0%, transparent 70%), 
                  #f5f7fa !important;
      background-attachment: fixed !important;
      background-size: cover !important;
      filter: saturate(2.0) contrast(1.2) !important;
      font-family: 'Montserrat', sans-serif;
      margin: 0;
      height: 100vh;
      overflow: hidden;
    }
    .container {
      position: absolute;
      -webkit-transform: translate(-50%, -50%);
      transform: translate(-50%, -50%);
      top: 50%;
      left: 50%;
    }

    form {
      background: rgba(255, 255, 255, 0.15);
      padding: 3em;
      border-radius: 24px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      -webkit-backdrop-filter: blur(25px) saturate(180%);
      backdrop-filter: blur(25px) saturate(180%);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      text-align: center;
      position: relative;
    }
    form p {
      font-weight: 900;
      color: #ffffff;
      font-size: 1.6rem;
      margin: 0;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
<div class="container">
  <form>
    <p><?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
</p>
  </form>
</div>
</body>
</html>
<?php }
}
