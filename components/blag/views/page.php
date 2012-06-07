<?php
// <editor-fold defaultstate="collapsed" desc="Header DocBlock">
/**
 * Magic Potion, view.php
 *
 * 
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	view
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2011
 * @link	http://web.mirakulix.org/
 */
// </editor-fold>
namespace MagicPotion;	

/**
 * 
 */
class BlagPageView extends View {
	public function __construct()
	{
		parent::__construct();
		try {
			$this->tpl = new Template('/components/blag/page');
			$this->tpl->set_component('blag', 'page');
		} catch(importException $e) {
			$this->logger->log_error($e);
		}
	}
	
	public function set_var($var, $val)
	{
		$this->tpl->set_tag($var, $val);
	}
	
	public function output()
	{
		return $this->tpl->output();
	}
}

/**
 * EOF /
 */