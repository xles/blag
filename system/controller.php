<?php
// <editor-fold defaultstate="collapsed" desc="Header DocBlock">
/**
 * Magic Potion, controller.php
 *
 * 
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	controller
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2011
 * @link	http://web.mirakulix.org/
 */
// </editor-fold>
namespace MagicPotion;

/**
 * 
 */
abstract class Controller {
	protected $uri;
	protected $logger;
	protected $auth;

	public function __construct()
	{
		$this->uri = new Rewrite();
		$this->auth = new Auth();
		$this->logger = Logger::get_instance();
	}
	
	abstract public function &invoke();	
}

/**
 * EOF /
 */
