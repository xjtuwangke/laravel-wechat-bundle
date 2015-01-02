<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/1/2
 * Time: 23:43
 */

namespace Xjtuwangke\LaravelWechatBundle;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class WechatUpdateMenuCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'wechat:updateMenu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';


    protected $uriPrefix = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=ACCESS_TOKEN';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        //
        $menu = \Config::get('laravel-wechat-bundle::config.menu');
        $options = \Config::get('laravel-wechat-bundle::config.options');
        $menu = json_encode( $menu , JSON_UNESCAPED_UNICODE );
        $wechat = new \Wechat\Wechat( $options );
        if( $wechat->createMenu( $menu ) ){
            $this->info( 'success!' );
        }
        else{
            $this->error( 'failed!' );
            $this->error( $wechat->errMsg );
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            //array('example', InputArgument::REQUIRED, 'An example argument.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            //array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}
