<?php
// <editor-fold defaultstate="collapsed" desc="Header DocBlock">
/**
 * Magic Potion, template.php
 *
 * 
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	template
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2011
 * @link	http://web.mirakulix.org/
 */
// </editor-fold>
namespace MagicPotion;

class componentNotFoundException extends \MagicPotion\Error {}
class controllerNotFoundException extends \MagicPotion\Error {}

/**
 * 
 */
class Router {
	private $route;
	private $routes = array();
	private $logger;
	private $component;
	
	public function __construct()
	{
		$this->logger = Logger::get_instance();
		$this->parse_routes();
	}
	
	public function route()
	{
		$uri = new Rewrite();
		$uri = $uri->request_URI();
		
		$uri = rtrim($uri,'/');
		
		if(empty($uri)) {
			$this->logger->log_line('Route empty, using default');
			$route = $this->routes['default'];
		} else {
			if($this->in_urlmap($uri)) {
				$this->logger->log_line('Route found, mapping');
				$route = $this->routes[$uri];
			} else {
				$this->logger->log_line('Assume standard format');
				$route = $uri;
			}
		}
		$this->route = $route;
	}

/*
		} catch (RouteNotFoundException $e) {
		} catch (badClassNameException $e) {
		} catch (classFileNotFoundException $e) {
		} catch (classNameNotFoundException $e) {
		} catch (classMethodNotFoundException $e) {
		} catch (classNotSpecifiedException $e) {
		} catch (methodNotSpecifiedException $e) {
 */
	public function dispatch()
	{
		$route = explode('/', $this->route, 2);
		$component = $route[0];

		try{
			$this->logger->log_line('Checking for component');
			import("com.$component.controller");
			$this->logger->log_state('Found.');
		} catch(importException $e) {
			$this->logger->log_state('Not found.');
			throw new componentNotFoundException($component);
		}
		$class_name = __NAMESPACE__.'\\'
				.ucfirst($component).'Controller';
		
		$this->logger->log_line('Checking for controller');
		if(!class_exists($class_name)) {
			$this->logger->log_state('Not found.');
			throw new controllerNotFoundException($class_name);
		} else {
			$this->logger->log_state('Found.');
			$this->component = $component;
		}
		
		return true;
	}
	
	public function in_urlmap($uri)
	{
		$this->logger->log_line('Checking routemap');
#		if(in_array($uri, $this->routes)) {
		if(isset($this->routes[$uri])) {
			$this->logger->log_state('Route found.');
			return TRUE;
		} else {
			$this->logger->log_state('Route not found.');
			return FALSE;
		}
	}
	
	public function get_component()
	{
		$class_name = __NAMESPACE__.'\\'
				.$this->component.'Controller';
		return $class_name;
	}
	
	public function load_cache()
	{
		return true;
	}
	
	public function parse_routes()
	{
		$this->routes = Helper::parse_ini('cfg.routes');
	}
}

/**
 * EOF includes/router.php
 */