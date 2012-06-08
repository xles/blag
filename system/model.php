<?php
// <editor-fold defaultstate="collapsed" desc="Header DocBlock">
/**
 * Magic Potion, model.php
 *
 * 
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	model
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2011
 * @link	http://web.mirakulix.org/
 */
// </editor-fold>
namespace MagicPotion;

/**
 * 
 */
abstract class Model extends Object{
	protected $db;
	protected $uri;
	protected $logger;
	protected $lang;
	protected $session;

	public function __construct()
	{
		$this->db = new Database();
		$this->uri = new Rewrite();
		$this->lang = new Locale();
		$this->logger = Logger::get_instance();
		$this->session = Session::get_instance();
	}
}

/**
 * EOF /system/model.php
 */
