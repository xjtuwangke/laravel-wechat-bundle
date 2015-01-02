<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/1/3
 * Time: 01:30
 */

namespace Xjtuwangke\LaravelWechatBundle;


class WechatCache implements Wechat\WechatCacheInterface{

    public function put( $key , $value , $expires ){
        $expiresAt = \Carbon\Carbon::now()->addSeconds( $expires );
        return \Cache::tags('wechat')->put( $key , $value , $expiresAt );
    }

    public function forget( $key ){
        return \Cache::tags('wechat')->forget( $key );
    }

    public function get( $key ){
        return \Cache::tags('wechat')->get( $key );
    }
}