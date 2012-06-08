<?php
/**
 * Magic Potion.
 *
 * MOAR INFOZ!!
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	error
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2010, http://web.mirakulix.org/
 */
namespace MagicPotion;

class Error extends \Exception {
	/*
	public function __construct($msg = NULL)
	{
		if(!$msg)
			$msg = 'Unknown '.get_class($this);
		parent::__construct(get_class($this).': '.$msg);
	}
	*/
	public function __toString()
	{
		return $this->getMessage()."\n";
	}

}

/**
 * EOF /inc/error.php
 */
