<?php
/**
 * Magic Potion, core.php
 *
 * 
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	application
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2011
 * @link	http://web.mirakulix.org/
 */
namespace MagicPotion;

include(ROOT.'/system/import.php');

import('sys.object');
import('sys.helper');
import('inc.config');
import('inc.error');
import('inc.logger');

import('sys.magicpotion');
import('sys.defines');


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

function import($str)
{
	$helper = new Import();
	return $helper->import($str);
}


/**
 * Wrapper for the logger to make it global within the namespace.
 */
function MPlog($str)
{
	$log = Logger::get_instance();
$log->log_msg('Logger initiated asd');
	$log->log_line($str);
}



$log = Logger::get_instance();
$log->log_msg('Logger initiated');

//$main = MagicPotion::get_instance();

$main = new MagicPotion();
$log->log_msg('Application instanciated, initiating');

/**
 * EOF /system/core.php
 */
