<?php
/**
 * Magic Potion.
 *
 * MOAR INFOZ!!
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2010, http://web.mirakulix.org/
 */
namespace MagicPotion;

class Session {
	
	private $logger;
	
	static public function &get_instance()
	{
		static $instance;
		if (!is_object($instance)) {
			$instance = new Session();
		}

		return $instance;
	}
	
	public function __construct()
	{
		$this->logger = Logger::get_instance();

		session_start();
	}
	
	public function set($var,$val)
	{
		$this->logger->log_line('Setting '.$var);
		if(empty($val) || empty($var)) {
			$this->logger->log_fail();
			return FALSE;
		} else {
			$_SESSION[$var] = $val;
			$this->logger->log_ok();
			return TRUE;
		}
	}
	
	public function get($var)
	{
		$this->logger->log_line('Getting '.$var);
		if(isset($_SESSION[$var])) {
			$this->logger->log_ok();
			return $_SESSION[$var];
		} else {
			$this->logger->log_fail();
			return FALSE;
		}
	}
	
	public function destroy($var)
	{
		$this->logger->log_line('Destroying '.$var);
		if(isset($_SESSION[$var])) {
			unset($_SESSION[$var]);
			$this->logger->log_ok();
			return TRUE;
		} else {
			$this->logger->log_fail();
			return FALSE;
		}
	}
	
	public function __destruct()
	{
		session_write_close();
	}
}
/**
 * EOF /inc/session.php
 */
