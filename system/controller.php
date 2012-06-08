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
abstract class Controller extends Object {
	protected $uri;
	protected $auth;
	protected $_component;

	public function __construct()
	{
		parent::__construct();
		$this->uri = new Rewrite();
		$this->auth = new Auth();
	}

	abstract public function &invoke();	

	protected function component($str)
	{
		$this->_component = $str;
		$this->log->log_line('Registred component '.$str);
	}

	protected function &load_model($str)
	{
		$class = __NAMESPACE__.'\\'.$this->_component
			.ucfirst($str).'Model';
		$model = new $class;
		return $model;
	}

	protected function &load_view($str)
	{
		$class = __NAMESPACE__.'\\'.$this->_component
			.ucfirst($str).'View';
		$view = new $class;
		$view->init(strtolower($this->_component), $str);
		return $view;
	}

	protected function get_method()
	{
		if(!empty($_POST)) {
			switch(strtolower($_POST['method'])) {
				case 'post':
					return 'post';
					break;
				case 'put':
					return 'put';
					break;
				case 'delete':
					return 'delete';
					break;
				default:
					return 'get';
					break;
			}
		} else {
			return 'get';
		}
	}
}

/**
 * EOF /system/controller.php
 */
