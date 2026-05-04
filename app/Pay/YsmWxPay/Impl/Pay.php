<?php
declare(strict_types=1);

namespace App\Pay\YsmWxPay\Impl;

use App\Entity\PayEntity;
use App\Pay\Base;
use Kernel\Exception\JSONException;

class Pay extends Base implements \App\Pay\Pay
{
    
    public function trade(): PayEntity
    {
        
        if (!$this->config['appid']) {
            throw new JSONException("请先配置商户APPID");
        }

        if (!$this->config['secret']) {
            throw new JSONException("请先配置商户AppSecret");
        }
        
        $url = 'https://www.yishoumi.cn/u/payment';
        $params = array();
        
        $params['appid'] = $this->config['appid'];
        $params['mch_orderid'] = $this->tradeNo;
        $params['description'] = $this->tradeNo;
        $params['total'] = (int) strval($this->amount * 100);
        $params['notify_url'] = $this->callbackUrl;
        $params['nopay_url'] = $this->returnUrl;
        $params['callback_url'] = $this->returnUrl;
        $params['time'] = time();
        $params['nonce_str'] = bin2hex(random_bytes(16));
        $params['plugin'] = 'acg';
        if (!Signature::isMobile()) {
            
            $params['payType'] = 2;
        }else{
            if(!empty($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){
                
                $params['payType'] = 1;
            }else{
                
                $params['payType'] = 3;
            }
        }
        $params['sign'] = Signature::HashSign($params, $this->config['secret']);
        $result = Signature::HttpPost($url, json_encode($params));
        
        if(!isset($result['code'])){
            throw new JSONException("支付接口调用失败");
        }
        if ($result['code'] != 0) {
            throw new JSONException((string)$result['msg']);
        }
        
        $payEntity = new PayEntity();
        if (!Signature::isMobile()) {
            $payEntity->setType(self::TYPE_LOCAL_RENDER);
            $payEntity->setUrl($result['url']);
        } else {
            $payEntity->setType(self::TYPE_REDIRECT);
            if(!empty($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){
                
                $payEntity->setUrl($result['url']);
            }else{
                
                $payEntity->setType(self::TYPE_LOCAL_RENDER);
                $payEntity->setUrl($result['url']);
            }
            
        }
        return $payEntity;
    }

}