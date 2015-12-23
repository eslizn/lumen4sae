<?php

namespace SaeService;

use Monolog\Handler\AbstractProcessingHandler;

/**
 * logger for sae
 *
 * @author eslizn <eslizn@gmail.com>
 *
 */
class Logger extends AbstractProcessingHandler
{
	/**
	 *
	 * {@inheritDoc}
	 * @see \Monolog\Handler\AbstractProcessingHandler::write()
	 */
	protected function write(array $record)
	{
		if (function_exists('sae_debug')) {
			sae_debug($record['formatted']);
		} else {
			error_log($record['formatted']);
		}
	}
}