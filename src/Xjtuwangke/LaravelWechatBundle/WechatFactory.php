<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/1/9
 * Time: 18:35
 */

namespace Xjtuwangke\LaravelWechatBundle;


class WechatFactory {

    /**
     * @param null|array $config
     * @return \Wechat\Wechat
     */
    public static function create( $config = null ){
        if( ! $config ){
            $config = \Config::get( 'laravel-wechat-bundle::config.options' );
        }
        return new \Wechat\Wechat( $config );
    }
}