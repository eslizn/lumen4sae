<?php

namespace SaeService;

use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
	public function register()
	{
		if (!defined('SAE_ACCESSKEY')) {
			return;
		}
		
		$this->app->make('Psr\Log\LoggerInterface')->setHandlers(array(new Logger()));
		
		/** @var \Illuminate\Cache\CacheManager $CacheManager **/
		$CacheManager = $this->app->make('cache');
		$CacheManager->extend('sae', function($app){
			return new Cache;
		});
		$CacheManager->setDefaultDriver('sae');
		$this->app['config']['cache.stores.sae'] = array('driver' => 'sae');
		
		/** @var \Illuminate\Session\SessionManager $SessionManager **/
		$SessionManager = $this->app->make('session');
		$SessionManager->extend('sae', function($app){
			return new Session;
		});
		$SessionManager->setDefaultDriver('sae');
	}
}
