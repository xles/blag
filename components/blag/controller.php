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

import('com.blag.models.blag');
import('com.blag.views.*');
import('com.blag.models.*');
/**
 * 
 */
class BlagController extends Controller {
	public function &invoke()
	{
		/* Register the component */
		$this->component('Blag');

		$uri = $this->uri->parse_URL('controller/model/query');
		
		switch($uri['model']) {
			case 'post':
				$content = $this->post();
				break;
			case 'page':
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
		$m = $this->load_model(__FUNCTION__);
		$v = $this->load_view(__FUNCTION__);
		#$v->bind('method',print_r($m->list_posts(), true));
		$v->bind('method',$m->get_username(1));
		return $v->output();
#		return $_SESSION['USERNAME'];
	}
	private function post()
	{
		switch($this->get_method()) {
			case 'post':
				$this->newpost();
				break;
			case 'get':
				$this->getpost();
				break;
			case 'put':
				$this->updatepost();
				break;
			case 'delete':
				$this->removepost();
				break;
		}
		#$model = $this->load_model(__FUNCTION__);
		$view = $this->load_view(__FUNCTION__);
		$view->bind('method',$this->get_method());
		return $view->output();
#		return $_SESSION['USERNAME'];
	}
}

/**
 * EOF /components/blag.php
 */
