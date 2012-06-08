<?php
// <editor-fold defaultstate="collapsed" desc="Header DocBlock">
/**
 * Magic Potion, helper.php
 *
 * 
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	helper
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2011
 * @link	http://web.mirakulix.org/
 */
// </editor-fold>
namespace MagicPotion;

/**
 * 
 */
class Helper {
	public static function parse_ini($file,$sections = NULL)
	{
		$helper = new Import();

		$bool_true  = array('on', 'true', 'enabled', 'yes');
		$bool_false = array('off','false','disabled','no' );

		$file = $helper->import($file);

		$tmp = array();
		foreach($file as $section => $vars) {
			foreach($vars as $var => $val) {
				if(is_numeric($val)) {
					$tmp[$section][$var] = (int) $val;
				} else if(in_array($val, $bool_true)) {
					$tmp[$section][$var] = TRUE;
				} else if(in_array($val, $bool_false)) {
					$tmp[$section][$var] = FALSE;
				} else if($val == strtolower('null')) {
					$tmp[$section][$var] = NULL;
				} else {
					$tmp[$section][$var] = $val;
				}
			}

		}

		if(is_null($sections)) {
			$r = array();
			foreach($tmp as $section => $vars) {
				$r = array_merge($r,$vars);
			}
		} else {
			$r = $tmp;
		}

		return $r;
	}
}


/**
 * EOF /
 */