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
	protected $lang;

	public function __construct()
	{
		parent::__construct();
		$this->db = new Database();
		$this->uri = new Rewrite();
		$this->lang = new Locale();
	}
}

/**
 * EOF /system/model.php
 */
