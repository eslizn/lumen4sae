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
		
		$this->app['config']['cache.stores.driver'] = 'sae';
		$this->app->make('cache')->extend('sae', function($app){
			return $app->make('cache')->repository(new Cache);
		});
		
		$this->app['config']['session.stores.driver'] = 'sae';
		$this->app->make('session')->extend('sae', function($app){
			return new Session;
		});
	}
}
