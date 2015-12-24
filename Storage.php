<?php

namespace SaeService;

use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * sae storage
 *
 * @author eslizn
 *
 */
class Storage implements Filesystem, Cloud
{
	
	/**
	 * 
	 * @var \SaeStorage $storage
	 */
	protected $storage;
	
	/**
	 * parse path
	 * 
	 * @param string $path
	 * @return array
	 */
	protected function parser($path) {
		$path = array_filter(explode('/', str_replace('\\', '/', $path)));
		return array(array_shift($path), implode('/', $path));
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::exists()
	 */
	public function exists($path)
	{
		list($domain, $path) = $this->parser($path);
		return $this->storage->fileExists($domain, $path);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::get()
	 */
	public function get($path)
	{
		list($domain, $path) = $this->parser($path);
		return $this->storage->read($domain, $path);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::put()
	 */
	public function put($path, $contents, $visibility = null)
	{
		list($domain, $path) = $this->parser($path);
		return $this->storage->write($domain, $path, $contents);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::getVisibility()
	 */
	public function getVisibility($path)
	{
		return static::VISIBILITY_PUBLIC;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::setVisibility()
	 */
	public function setVisibility($path, $visibility)
	{
		if ($visibility === static::VISIBILITY_PRIVATE) {
			return false;
		}
		return true;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::prepend()
	 */
	public function prepend($path, $data)
	{
		return $this->put($path, $data . $this->get($path));
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::append()
	 */
	public function append($path, $data)
	{
		return $this->put($path, $this->get($path) . $data);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::delete()
	 */
	public function delete($paths)
	{
		list($domain, $path) = $this->parser($paths);
		return $this->storage->delete($domain, $path);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::copy()
	 */
	public function copy($from, $to) {
		return $this->put($to, $this->get($from)) ? true : false;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::move()
	 */
	public function move($from, $to)
	{
		return $this->copy($from, $to) && $this->delete($from);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::size()
	 */
	public function size($path)
	{
		list($domain, $path) = $this->parser($path);
		$info = $this->storage->getAttr($domain, $path);
		return $info ? $info['length'] : false;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::lastModified()
	 */
	public function lastModified($path)
	{
		list($domain, $path) = $this->parser($path);
		$info = parent::getAttr($domain, $path);
		return $info ? $info['datetime'] : false;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::files()
	 */
	public function files($directory = null, $recursive = false)
	{
		list($domain, $path) = $this->parser($directory);
		$list = array();
		$offset = 0;
		do {
			$result = $this->storage->getListByPath($domain, $path, 1000, $offset, !$recursive);
			foreach ($result['files'] as $item) {
				$list[] = $domain . '/' . $item['fullName'];
			}
			$size = sizeof($result['dirNum'] + $result['fileNum']);
			$offset += $size;
		} while ($size >= 1000);
		return $list;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::allFiles()
	 */
	public function allFiles($directory = null)
	{
		return $this->files($directory, true);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::directories()
	 */
	public function directories($directory = null, $recursive = false)
	{
		list($domain, $path) = $this->parser($directory);
		$list = array();
		$offset = 0;
		do {
			$result = $this->storage->getListByPath($domain, $path, 1000, $offset, !$recursive);
			foreach ($result['dirs'] as $item) {
				$list[] = $domain . '/' . $item['fullName'];
			}
			$size = sizeof($result['dirNum'] + $result['fileNum']);
			$offset += $size;
		} while ($size >= 1000);
		return $list;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::allDirectories()
	 */
	public function allDirectories($directory = null)
	{
		return $this->directories($directory, true);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::makeDirectory()
	 */
	public function makeDirectory($path)
	{
		return true;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Filesystem\Filesystem::deleteDirectory()
	 */
	public function deleteDirectory($directory)
	{
		list($domain, $path) = $this->parser($directory);
		return $this->storage->delete($domain, $path);
	}
	
}