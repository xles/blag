<?php
/**
 * 
 */
namespace MagicPotion;

abstract class Object {
	protected $log;
	protected $session;
	#protected $error;

	protected function __construct() {
		$this->log = Logger::get_instance();
		$this->session = Session::get_instance();
		#$this->error = Error::get_instance();
	}
}

/**
 * EOF /system/object.php
 */
