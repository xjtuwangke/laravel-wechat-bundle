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

    protected $revData = array();

    public function __construct(){

        if( $this->logger ){
            $context = array(
                'input' => \Input::all() ,
                'header' => \Request::header() ,
            );
            WechatLogger::access( 'access' , $context );
        }

        $this->wechat = new \Wechat\Wechat( \Config::get( 'laravel-wechat-bundle::config.options' ) );
        $this->wechat->valid();

        if( $this->logger ){
            WechatLogger::access( 'content' , [ $this->wechat->getRev() ] );
        }
        $this->msgType = $this->wechat->getRev()->getRevType();
        $this->open_id = $this->wechat->getRevFrom();
        $this->revData = $this->wechat->getRevData();
        $this->revDataDot = array_dot( $this->revData );
        $this->msg_id = $this->getRevData( 'MsgId' );
    }

    public function getRevData( $key , $default = null , $data = array() ){
        $key = strtolower( $key );
        foreach( $this->revDataDot as $name => $value ){
            if( strtolower( $name ) == $key ){
                return $value;
            }
        }
        return $default;
    }

    public function getRevDataArray( array $keys ){
        $array = [];
        foreach( $keys as $key ){
            $array[$key] = $this->getRevData( $key );
        }
        return $array;
    }

    public function serve(){
        switch( $this->msgType ) {
            case \Wechat\Wechat::MSGTYPE_TEXT:
                $this->onText( $this->getRevData( 'content' ) );
                break;
            case \Wechat\Wechat::MSGTYPE_IMAGE:
                $this->onImage( $this->wechat->getRevData( 'PicUrl' )  , $this->wechat->getRevData( 'MediaId' ) );
                break;
            case \Wechat\Wechat::MSGTYPE_VOICE:
                $this->onVoice( $this->wechat->getRevData( 'Format' )  , $this->wechat->getRevData( 'MediaId' ) );
                break;
            case \Wechat\Wechat::MSGTYPE_VIDEO:
                $this->onVideo( $this->wechat->getRevData( 'ThumbMediaId' )  , $this->wechat->getRevData( 'MediaId' ) );
                break;
            case \Wechat\Wechat::MSGTYPE_LOCATION:
                $this->onLocation(
                    $this->wechat->getRevData( 'Location_X' ) ,
                    $this->wechat->getRevData( 'Location_Y' ) ,
                    $this->wechat->getRevData( 'Scale' ) ,
                    $this->wechat->getRevData( 'Label' )
                );
                break;
            case \Wechat\Wechat::MSGTYPE_LINK:
                $this->onLink(
                    $this->wechat->getRevData( 'Title' ) ,
                    $this->wechat->getRevData( 'Description') ,
                    $this->wechat->getRevData( 'Url')
                );
                break;
            case \Wechat\Wechat::MSGTYPE_EVENT:
                $this->onEvent( $this->getRevData( 'Event' ) );
                break;

            default:
                $this->onUnknown();
        }
    }

    public function onText( $content ){}

    public function onImage( $url , $mediaId ){}

    public function onVoice( $format , $mediaId ){}

    public function onVideo( $thumbMediaId , $mediaId ){}

    public function onLocation( $x , $y , $scale , $label ){}

    public function onLink( $title , $desc , $url ){}

    public function onUnknown(){}

    public function onEvent( $event ){
        switch( strtolower( $event ) ){
            case 'subscribe':
                $qrscene = $this->getRevData( 'EventKey' );
                $ticket = $this->getRevData( 'Ticket' );
                if( $qrscene && $ticket ){
                    $qrscene = substr( $qrscene , 8 );
                }
                $this->onEventSubscribe( $qrscene , $ticket );
                break;
            case 'unsubscribe':
                $this->onEventUnsubscribe();
                break;
            case 'scan':
                $qrscene = $this->getRevData( 'EventKey' );
                $ticket = $this->getRevData( 'Ticket' );
                $this->onEventScan( $qrscene , $ticket );
                break;
            case 'location':
                $this->onEventLocation(
                    $this->getRevData( 'Latitude' ) ,
                    $this->getRevData( 'Longitude' ) ,
                    $this->getRevData( 'Precision' )
                );
                break;
            case 'click':
                $this->onEventClick(
                    $this->getRevData( 'EventKey' )
                );
                break;
            case 'view':
                $this->onEventView(
                    $this->getRevData( 'EventKey' )
                );
                break;
            case 'scancode_push':
                $this->onEventScancodePush(
                    $this->getRevData( 'EventKey' ) ,
                    $this->getRevData( 'ScanCodeInfo.ScanType' ) ,
                    $this->getRevData( 'ScanCodeInfo.ScanResult' )
                );
                break;
            case 'scancode_waitmsg':
                $this->onEventScancodeWaitmsg(
                    $this->getRevData( 'EventKey' ) ,
                    $this->getRevData( 'ScanCodeInfo.ScanType' ) ,
                    $this->getRevData( 'ScanCodeInfo.ScanResult' )
                );
                break;
            case 'location_select':
                $this->onEventLocationSelect(
                    $this->getRevData( 'EventKey' ) ,
                    $this->getRevData( 'SendLocationInfo.Location_X' ) ,
                    $this->getRevData( 'SendLocationInfo.Location_Y' ) ,
                    $this->getRevData( 'SendLocationInfo.Scale' ) ,
                    $this->getRevData( 'SendLocationInfo.Label' ) ,
                    $this->getRevData( 'SendLocationInfo.Poiname' )
                );
                break;
            case 'pic_sysphoto':
                $this->onEventPicSysphoto(
                    $this->getRevData( 'EventKey' ) ,
                    $this->getRevData( 'SendPicsInfo.Count' ) ,
                    $this->wechat->getRevSendPicsInfo()
                );
                break;
            case 'pic_photo_or_album':
                $this->onEventPicPhotoOrAlbum(
                    $this->getRevData( 'EventKey' ) ,
                    $this->getRevData( 'SendPicsInfo.Count' ) ,
                    $this->wechat->getRevSendPicsInfo()
                );
                break;
            case 'pic_weixin':
                $this->onEventPicWeixin(
                    $this->getRevData( 'EventKey' ) ,
                    $this->getRevData( 'SendPicsInfo.Count' ) ,
                    $this->wechat->getRevSendPicsInfo()
                );
                break;
            default:
                $this->onEventUnknown();
        }
    }

    public function onEventScancodePush( $eventKey , $scanType , $scanResult ){}

    public function onEventScancodeWaitmsg( $eventKey , $scanType , $scanResult ){}

    public function onEventSubscribe( $qrscene , $ticket ){}

    public function onEventUnsubscribe(){}

    public function onEventScan( $qrscene , $ticket ){}

    public function onEventLocation( $lat , $log , $precision ){}

    public function onEventLocationSelect( $eventKey , $lat , $log , $scale , $label , $poiname ){}

    public function onEventClick( $key ){}

    public function onEventView( $view ){}

    public function onEventUnknown(){}

    public function onEventPicSysphoto( $eventKey , $count , $picInfo ){}

    public function onEventPicPhotoOrAlbum( $eventKey , $count , $picInfo ){}

    public function onEventPicWeixin( $eventKey , $count , $picInfo ){}




}
