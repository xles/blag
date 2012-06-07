<?php
/**
 * Magic Potion.
 *
 * MOAR INFOZ!!
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	error
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2010, http://web.mirakulix.org/
 */
namespace MagicPotion;

class Error extends \Exception {
	/*
	public function __construct($msg = NULL)
	{
		if(!$msg)
			$msg = 'Unknown '.get_class($this);
		parent::__construct(get_class($this).': '.$msg);
	}
	*/
	public function __toString()
	{
		return $this->getMessage()."\n";
	}

/**
 * Exception handler.
 *
 * @param string $e
 */
	function exception_handler($e)
	{
		if(DEBUG == TRUE) {
			$msg = $e;
		} else {
			$msg = get_class($e).': '.$e->getMessage();
		}
		
		$logger = Logger::get_instance();
		$logger->log_msg('Fatal error detected');
		$config = Config::get_instance('look_and_feel');
		#$tpl = new \MagicPotion\Template('/system/uncaught_exception');
		#$tpl->set_tag('page_title', $config->get('page_title'));
		#$tpl->set_tag('message', $msg);
		$logger->log_msg($msg);
		#$tpl->render();
		$logger->log_msg('Application halted');
		$logger->debugger(true);

	}

/**
 * Error handler.
 *
 * @param integer $errno
 * @param string $errstr
 * @param string $errfile
 * @param integer $errline
 * @return void
 */
	function error_handler($errno, $errstr, $errfile, $errline)
	{
		$errtype = array(
			E_ERROR => 'Error',
			E_WARNING => 'Warning',
			E_PARSE => 'Parsing error',
			E_NOTICE => 'Notice',
			E_CORE_ERROR => 'Core error',
			E_CORE_WARNING => 'Core warning',
			E_COMPILE_ERROR => 'Compile error',
			E_COMPILE_WARNING => 'Compile warning',
			E_USER_ERROR => 'User error',
			E_USER_WARNING => 'User warning',
			E_USER_NOTICE => 'User notice',
			E_STRICT => 'Strict notice',
			E_RECOVERABLE_ERROR => 'Recoverable error'
		);

		if (!(error_reporting() & $errno)) {
			return;
		}

		$msg = $errtype[$errno].': '.$errstr.' in '.
			$errfile.' on line '.$errline;
		$logger = Logger::get_instance();
		$logger->log_error($msg);
	}
}

/**
 * EOF /inc/error.php
 */
