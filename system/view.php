<?php
namespace MagicPotion;

/**
 * 
 */
abstract class View extends Object {
	protected $vars = array();
	protected $tpl;

	public function init($component, $model)
	{
		try {
			$tpl = '/components/'.$component.'/'.$model;
			$this->tpl = new Template($tpl);
			$this->tpl->set_component($component, $model);
		} catch(importException $e) {
			$this->log->log_error($e);
		}
	}
	
	public function bind($var, $val)
	{
		$this->tpl->set_tag($var, $val);
	}

	public function output()
	{
		return $this->tpl->output();
	}

	public function syndication()
	{
		return false;
	}
}

/**
 * EOF /system/view.php
 */
