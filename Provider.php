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
		
		//config log
		$this->app->make('Psr\Log\LoggerInterface')->setHandlers(array(new Logger()));
		
		//config db
		$this->app['config']['database.connections.mysql.write.host'] = SAE_MYSQL_HOST_M;
		$this->app['config']['database.connections.mysql.read.host'] = SAE_MYSQL_HOST_S;
		$this->app['config']['database.connections.mysql.port'] = SAE_MYSQL_PORT;
		$this->app['config']['database.connections.mysql.database'] = SAE_MYSQL_DB;
		$this->app['config']['database.connections.mysql.username'] = SAE_MYSQL_USER;
		$this->app['config']['database.connections.mysql.password'] = SAE_MYSQL_PASS;
		
		//config cache
		/** @var \Illuminate\Cache\CacheManager $CacheManager **/
		$CacheManager = $this->app->make('cache');
		$CacheManager->extend('sae', function($app){
			return new Cache;
		});
		$CacheManager->setDefaultDriver('sae');
		$this->app['config']['cache.stores.sae'] = array('driver' => 'sae');
		
		//config session
		/** @var \Illuminate\Session\SessionManager $SessionManager **/
		$SessionManager = $this->app->make('session');
		$SessionManager->extend('sae', function($app){
			return new Session;
		});
		$SessionManager->setDefaultDriver('sae');
		
		//config storage
		/** @var \Illuminate\Filesystem\FilesystemManager $FilesystemManager **/
		$FilesystemManager = $this->app->make('storage');
		$FilesystemManager->extend('sae', function($app){
			return new Storage;
		});
	}
}
