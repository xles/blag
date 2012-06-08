<?php
/**
 * Magic Potion.
 *
 * MOAR INFOZ!!
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2010, http://web.mirakulix.org/
 */
namespace MagicPotion;
use MagicPotion\Helper;

class Locale {

	private $file;
	private $session;
	private $component;
	private $section;
	private $logger;

	public function  __construct()
	{
		$config = Config::get_instance('look_and_feel');
		$this->logger = Logger::get_instance();
		$this->session = Session::get_instance();
		if(!$this->session->get('LANG'))
			$this->set_language($config->get('language'));
		$this->file = $this->parse_locale();
	}

	public function line($str, $int = FALSE)
	{
		if($int) {
			$line = $this->file[$str][$int];
		} else {
			$line = $this->file[$str];
		}
		return $line;
	}

	public function set_language($str)
	{
		$this->session->set('LANG',$str);
		
		$this->logger->log_line('Language set to '.$str);
		/* Update cookie junk. */
	}

	public function set_component($component, $section = FALSE)
	{
		if($section)
			$this->section = $section;
		$this->component = $component;
		$this->logger->log_line('Component set to '.$component);
		$this->file = $this->parse_locale();
	}

	private function parse_locale()
	{
		if(empty($this->component))
			$this->component = 'system';

		$lang = strtolower($this->session->get('LANG'));
		$com = $this->component;
		
		if(!empty($this->section)) {
			$tmp = Helper::parse_ini("loc.$lang.$com", TRUE);
			$vars = $tmp[$this->section];
			/* Overwrites */
			$vars = array_merge($vars, $tmp['global']);
		} else {
			$vars = Helper::parse_ini("loc.$lang.$com");
		}
		$this->logger->log_line("Parsed loc.$lang.$com");
		return $vars;
	}
}

/**
 * EOF /includes/locale.php
 */
