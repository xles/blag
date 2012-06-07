<?php
// <editor-fold defaultstate="collapsed" desc="Header DocBlock">
/**
 * Magic Potion, config.php
 *
 * 
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	config
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2011
 * @link	http://web.mirakulix.org/
 */
// </editor-fold>
namespace MagicPotion;
use MagicPotion\Helper;

/**
 * 
 */
class Config {
	private $config;
	private $static;
	private $section;

	static public function &get_instance($section = 'site')
	{
		static $instance;
		if (!is_object($instance)) {
			$instance = new Config();
		}
		$instance->section = $section;
		return $instance;
	}
	
	private function __construct()
	{
		$this->config = $this->parse_config();
	}
	
	public function set_section($str)
	{
		$this->section = $str;
	}

	public function get($str)
	{
		if(isset($this->config[$this->section][$str]))
			return $this->config[$this->section][$str];
		else
			return FALSE;
	}

	public function set($str)
	{

	}

	private function parse_config()
	{
		$file = ROOT.'/configuration.ini';
		$tmp = Helper::parse_ini('cfg.system', TRUE);
		$this->static = $tmp['site']['database_config'];
		$vars = $tmp;
		return $vars;
	}
}
/**
 * EOF /
 */