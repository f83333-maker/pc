<?php
declare(strict_types=1);

namespace App\View\User\Theme\Cartoon;

use App\Consts\Render;

interface Config
{

    const INFO = [
        "NAME" => "默认模板",
        "AUTHOR" => "荔枝",
        "VERSION" => "1.2.7",
        "WEB_SITE" => "#",
        "DESCRIPTION" => "默认模板",
        "RENDER" => Render::ENGINE_SMARTY
    ];

    const SUBMIT = [
        [
            "title" => "ICP备案号",
            "name" => "icp",
            "type" => "input",
            "placeholder" => "填写后将会在店铺底部显示ICP备案号，不填写则不显示。"
        ]
    ];

    const THEME = [
        "INDEX" => "Index/Index.html", 
        "CLOSED" => "Index/Closed.html", 
        "QUERY" => "Index/Query.html", 
        "LOGIN" => "Authentication/Login.html", 
        "REGISTER" => "Authentication/Register.html", 
        "FORGET_EMAIL" => "Authentication/ForgetEmail.html", 
        "FORGET_PHONE" => "Authentication/ForgetPhone.html", 
        "RECHARGE" => "User/Recharge.html", 
        "BILL" => "User/Bill.html", 
        "BUSINESS" => "User/Business.html", 
        "CATEGORY" => "User/Category.html", 
        "COMMODITY" => "User/Commodity.html", 
        "CARD" => "User/Card.html", 
        "COUPON" => "User/Coupon.html", 
        "CASH" => "User/Cash.html", 
        "CASH_RECORD" => "User/CashRecord.html", 
        "PERSONAL" => "User/Personal.html", 
        "EMAIL" => "User/Email.html", 
        "PHONE" => "User/Phone.html", 
        "PASSWORD" => "User/Password.html", 
        "ORDER" => "User/Order.html", 
        "AGENT_MEMBER" => "Agent/Member.html", 
    ];

}
