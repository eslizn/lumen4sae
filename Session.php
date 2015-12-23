<?php

namespace SaeService;

/**
 * 
 * @author eslizn
 *
 */
class Session extends \SaeKV implements \SessionHandlerInterface
{
	
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		$this->init();
	}
	
	/**
	 * 
	 * @param string $id
	 * @return string
	 */
	protected function getPrefix($id) {
		return 'session_' . $id;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see SessionHandlerInterface::open()
	 */
	public function open($path, $name)
	{
		return true;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see SessionHandlerInterface::close()
	 */
	public function close()
	{
		return true;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see SessionHandlerInterface::read()
	 */
	public function read($id)
	{
		return $this->get($this->getPrefix($id));
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see SessionHandlerInterface::write()
	 */
	public function write($id, $data)
	{
		$this->set($this->getPrefix($id), $data);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see SessionHandlerInterface::destroy()
	 */
	public function destroy($id)
	{
		$this->delete($this->getPrefix($id));
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see SessionHandlerInterface::gc()
	 */
	public function gc($lifetime)
	{
		//@todo
		return true;
	}
	
}