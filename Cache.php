<?php

namespace SaeService;

use Illuminate\Contracts\Cache\Store;
use Illuminate\Cache\TaggableStore;

/**
 * sae cache (memcache)
 * 
 * @author eslizn
 *
 */
class Cache extends TaggableStore implements Store
{
	
	/**
	 * 
	 * @var resource
	 */
	protected $memcache;
	
	/**
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->memcache = memcache_init();
	}
	
	/**
	 * Retrieve an item from the cache by key.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function get($key)
	{
		return memcache_get($this->memcache, $key);
	}
	
	/**
	 * Store an item in the cache for a given number of minutes.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @param  int     $minutes
	 * @return void
	 */
	public function put($key, $value, $minutes)
	{
		return memcache_set($this->memcache, $key, $value, 0, $minutes * 60);
	}
	
	/**
	 * Increment the value of an item in the cache.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return int|bool
	 */
	public function increment($key, $value = 1)
	{
		return memcache_increment($this->memcache, $key, $value);
	}
	
	/**
	 * Decrement the value of an item in the cache.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return int|bool
	 */
	public function decrement($key, $value = 1)
	{
		return memcache_decrement($this->memcache, $key, $value);
	}
	
	/**
	 * Store an item in the cache indefinitely.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return array|bool
	 */
	public function forever($key, $value)
	{
		return $this->put($key, $value, 0);
	}
	
	/**
	 * Remove an item from the cache.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function forget($key)
	{
		return memcache_delete($this->memcache, $key);
	}
	
	/**
	 * Remove all items from the cache.
	 *
	 * @return void
	 */
	public function flush()
	{
		return memcache_flush($this->memcache);
	}
	
	/**
	 * Get the cache key prefix.
	 *
	 * @return string
	 */
	public function getPrefix()
	{
		return '';
	}
	
}