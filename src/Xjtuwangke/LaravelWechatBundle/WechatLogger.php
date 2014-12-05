<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/12/6
 * Time: 03:39
 */

namespace Xjtuwangke\LaravelWechatBundle;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class WechatLogger {

    protected static function prepare( $type ){
        $path = storage_path( 'logs/wechat' );
        $file = $path . '/' . $type . '-' . date('Y-m-d') . '.log';
        if( ! \File::isDirectory( $path ) ){
            \File::makeDirectory( $path );
        }
        return $file;
    }

    public static function access( $message , $context = array() ){
        $logger = new Logger( 'api_log' );
        $file = static::prepare( 'access' );
        $logger->pushHandler( new StreamHandler( $file , Logger::INFO ));
        $logger->addInfo( $message , $context );
    }

    public static function warning( $message , $context = array() ){
        $logger = new Logger( 'api_log' );
        $file = static::prepare( 'warning' );
        $logger->pushHandler( new StreamHandler( $file , Logger::INFO ));
        $logger->warning( $message , $context );
    }

}