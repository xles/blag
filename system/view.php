<?php
namespace MagicPotion;

/**
 * 
 */
abstract class View {
	protected $vars = array();
	protected $logger;
	protected $tpl;

	public function __construct()
	{
		$this->logger = Logger::get_instance();
	}
	
	abstract public function output();	
}

/**
 * EOF /
 */
