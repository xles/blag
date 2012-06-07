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

import('com.blag.views.*');
import('com.blag.models.*');
/**
 * 
 */
class BlagController extends Controller {
	public function &invoke()
	{
		/*
		 * Register the component
		 */
		$this->component = 'Blag';
		
		$uri = $this->uri->parse_URL('controller/model/query');
		
		switch($uri['model']) {
			default:
				$content = $this->page();
				break;
		}

		try {
			$tpl = new Template('/design');
			$tpl->set_tag('content', $content);
			return $tpl;
		} catch(importException $e) {
			$this->logger->log_error($e);
			throw new controller;
		}
	}
	
	private function page()
	{
		$model = $this->load_model(__FUNCTION__);
		$view = $this->load_view(__FUNCTION__);
		$view->set_var('method',$_SERVER['REQUEST_METHOD']);
		return $view->output();
#		return $_SESSION['USERNAME'];
	}
}

/**
 * EOF components/user/controller.php
 */
 