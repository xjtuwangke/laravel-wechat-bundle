<?php namespace Xjtuwangke\LaravelWechatBundle;

use Illuminate\Support\ServiceProvider;

class LaravelWechatBundleServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('xjtuwangke/laravel-wechat-bundle');
		\Wechat\Wechat::setCache( new WechatCache() );
		$this->app['command.wechat.updateMenu'] = $this->app->share(
			function () {
				return new WechatUpdateMenuCommand;
			}
		);
		$this->commands( 'command.wechat.updateMenu' );
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
