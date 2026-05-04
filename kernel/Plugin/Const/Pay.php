<?php
declare (strict_types=1);

namespace Kernel\Plugin\Const;

interface Pay
{
    
    const RENDER_JUMP = 0;

    const RENDER_FORM_POST_SUBMIT = 1;

    const RENDER_LOCAL_PLUGIN_VIEW = 2;

    const RENDER_COMMON_ALIPAY_VIEW = 3;

    const RENDER_COMMON_WECHAT_VIEW = 4;

    const RENDER_COMMON_QQ_VIEW = 5;
}