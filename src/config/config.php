<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/1/2
 * Time: 23:39
 */

return array(

    'options' => array(
        'appid'=>'', //填写高级调用功能的app id, 请在微信开发模式后台查询
        'appsecret'=>'', //填写高级调用功能的密钥
        'token'=>'', //填写你设定的token
        'encodingaeskey'=>'', //填写加密用的EncodingAESKey
        //'partnerid'=>'88888888', //财付通商户身份标识，支付权限专用，没有可不填
        //'partnerkey'=>'', //财付通商户权限密钥Key，支付权限专用
        //'paysignkey'=>'' //商户签名密钥Key，支付权限专用
    ),

    'menu' => array(
        'button' => array(
            0 => array(
                'name' => '介绍' ,
                'sub_button' => array(
                    0 => array(
                        'type' => 'click' ,
                        'name' => '产品展示' ,
                        'key' => 'KEY_PRODUCTS' ,
                    ) ,
                    1 => array(
                        'type' => 'click' ,
                        'name' => '产品原料' ,
                        'key' => 'KEY_MATERIALS' ,
                    ) ,
                    2 => array(
                        'type' => 'click' ,
                        'name' => '开店初衷' ,
                        'key' => 'KEY_OUR_GOAL' ,
                    ) ,
                ),
            ),
            1 => array(
                'type' => 'view' ,
                'name' => '商城' ,
                'url' => 'http://wd.koudai.com/?userid=160620251' ,
            ),
            2 => array(
                'name' => '近期活动' ,
                'type' => 'click' ,
                'key' => 'KEY_ACTIVITIES'
            ),
        )
    ) ,
);