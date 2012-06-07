<?php
// <editor-fold defaultstate="collapsed" desc="Header DocBlock">
/**
 * Magic Potion, application.php
 *
 * 
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	application
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2011
 * @link	http://web.mirakulix.org/
 */
// </editor-fold>
namespace MagicPotion;

//include_once(ROOT.'/system/helper.php');

import('sys.defines');
import('inc.config');
import('inc.error');
import('inc.logger');

/**
 * Description of application
 *
 * @author xles
 */
class MagicPotion {
	private $start;
	private $router;
	private $logger;
	
	static public function &get_instance()
	{
		static $instance;
		if (!is_object($instance)) {
			$instance = new MagicPotion();
		}

		return $instance;
	}
	
	public function __construct()
	{
		if(DEBUG)
			$this->start = microtime();
		
		set_exception_handler(array(new Error,'exception_handler'));

		set_error_handler(array(new Error,'error_handler'));
		
		$this->logger = Logger::get_instance();
	}
	
	public function init() 
	{
		$this->logger->log_line('Initiating application');
		
		/**
		 * Including libraries.
		 */
		import('sys.controller');
		import('sys.model');
		import('sys.view');
		import('sys.includes');

		$config = Config::get_instance('look_and_feel');
		$this->session = Session::get_instance();
		$this->logger->log_line('Sessions enabled');
		
		/**
		 * Set language session variable to config value.
		 */
		$this->session->set('LANG',$config->get('language'));
		$this->logger->log_line('Language set to '.$config->get('language'));
		
		$this->router = new Router();
		
		$tpl_path = 'templates/'.$config->get('template');
		define('TPL_ROOT', ROOT.DIRECTORY_SEPARATOR.$tpl_path);
		define('TPL_BASE', BASEPATH.DIRECTORY_SEPARATOR.$tpl_path);

		$this->session->set('UID', 1);
		$this->session->set('USERNAME', 'xles');
	}
	
	public function run()
	{
		$this->logger->log_line('Running application');
		$component = $this->router->get_component();
		
		$component = new $component;
		
		$auth = new Auth();
		$loggedin = $auth->is_logged_in();
		
		try {
			$tpl = $component->invoke();
			$tpl->set_tag('loggedin', $loggedin);
			$tpl->render();
		} catch (authNeededUserException $e) {
			$uri = new Rewrite();
			$query = $uri->request_URI();
			$url = $uri->format_URL('user/login/'.$query);
			$msg = 'Authentication needed';
			$msg .= ' <a href="'.$url.'">Log in?</a>';
			$this->show_error('401',$msg);
			$this->logger->log_line($msg);
		} catch (forbiddenUserException $e) {
			$msg = 'Forbidden';
			$this->show_error('403',$msg);
			$this->logger->log_line($msg);
		}
	}
		
	public function route() 
	{
		$this->logger->log_line('Routing application');
		$this->router->route();
	}
	
	public function dispatch()
	{
		$this->logger->log_line('Dispatching application');
		try {
			$this->router->dispatch();
		} catch (RouteNotFoundException $e) {
			PageError::show('404', $url);
			$this->logger->log_line('404 Route not found');
		} catch (componentNotFoundException $e) {
			$msg = '400 Component "'.rtrim($e).'" not found';
			$this->show_error('400',$msg);
			$this->logger->log_line($msg);
		} catch (controllerNotFoundException $e) {
			$msg = '400 '.rtrim($e).' not found';
			$this->show_error('400',$msg);
			$this->logger->log_line($msg);
		}
	}
	
	private function show_error($file, $msg)
	{
		$this->logger->log_msg('Fatal error detected');
		$config = Config::get_instance('look_and_feel');
		$tpl = new \MagicPotion\Template('/system/'.$file);
		$tpl->set_tag('page_title', $config->get('page_title'));
		$tpl->set_tag('message', $msg);
		$tpl->render();
		$this->logger->log_msg('Application halted');
		$this->logger->debugger(true);
		exit;
	}
	
	
	public function __destruct()
	{
		if(DEBUG) {
			$time = microtime()-$this->start;
			$this->logger->log_msg('Build completed in '.$time.'s');
		}
	}
}

/**
 * EOF /application/application.php
 */