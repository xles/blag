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
	protected $component;

	public function __construct()
	{
		$this->uri = new Rewrite();
		$this->auth = new Auth();
		$this->logger = Logger::get_instance();
	}

	protected function import($str)
	{
		import($str);
	}
	
	abstract public function &invoke();	

	protected function &load_model($str)
	{
		$class = __NAMESPACE__.'\\'.$this->component
			.ucfirst($str).'Model';
		$model = new $class;
		return $model;
	}

	protected function &load_view($str)
	{
		$class = __NAMESPACE__.'\\'.$this->component
			.ucfirst($str).'View';
		$model = new $class;
		return $model;
	}
}

/**
 * EOF /
 */
