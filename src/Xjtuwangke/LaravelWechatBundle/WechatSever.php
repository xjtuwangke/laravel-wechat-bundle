<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/12/6
 * Time: 02:42
 */

namespace Xjtuwangke\LaravelWechatBundle;


class WechatSever extends \Controller{

    protected $wechat = null;

    protected $msgType = null;

    protected $open_id = null;

    protected $logger = true;

    public function __construct(){

        if( $this->logger ){
            $context = array(
                'input' => \Input::all() ,
                'header' => \Request::header() ,
            );
            WechatLogger::access( 'access' , $context );
        }

        $this->wechat = new \Wechat\Wechat( \Config::get( 'laravel-wechat-bundle::options' ) );
        $this->wechat->valid();

        if( $this->logger ){
            WechatLogger::access( 'content' , [ $this->wechat->getRev() ] );
        }

        $this->msgType = $this->wechat->getRev()->getRevType();
        $this->open_id = $this->wechat->getRev()->getRevFrom();
        $this->serve();
    }

    public function serve(){
        switch( $this->msgType ) {
            case \Wechat\Wechat::MSGTYPE_TEXT:
                $this->wechat->text("hello, I'm wechat")->reply();
                break;
            case \Wechat\Wechat::MSGTYPE_EVENT:
                break;
            case \Wechat\Wechat::MSGTYPE_IMAGE:
                break;
            default:
                $this->wechat->text("help info")->reply();
        }
    }

}