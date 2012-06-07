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

class importException extends \Exception {}

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
	
	public static function import($str)
	{
		$helper = new Import();
		return $helper->import($str);
	}
}

class Import {
	public function import($str)
	{
		$dir = explode ('.',$str);
		switch($dir[0]) {
			case 'sys': /* System core directory */
				$dir[0] = 'system';
				$this->parse_path($dir, '.php');
				break;
			case 'cfg': /* locale directory */
				$dir[0] = 'config';
				return $this->include_ini($dir);
				break;
			case 'com': /* com.component. */
				$dir[0] = 'components';
				$this->parse_path($dir, '.php');
				break;
			case 'inc': /* include directory */
				$dir[0] = 'includes';
				$this->parse_path($dir, '.php');
				break;
			case 'lib': /* library directory */
				$dir[0] = 'libraries';
				$this->parse_lib($dir);
				break;
			case 'loc': /* locale directory */
				$dir[0] = 'locales';
				return $this->include_ini($dir);
				break;
			case 'mod': /* module directory */
				$dir[0] = 'modules';
				$this->parse_mod($dir);
				break;
			case 'src': /* source directory */
				$dir[0] = 'src';
				$this->parse_src($dir);
				break;
			case 'tpl': /* template directory */
				$this->parse_tpl($dir);
				break;
		}
	}
	
	private function parse_path($dir = array(), $ext)
	{
		$path = ROOT;
		foreach($dir as $element) {
			if($element == '*') {
				$this->include_r($path);
				return TRUE;
			}
			$path .= DS.$element;
		}
		$path .= $ext;
		
		if(file_exists($path)) {
			if(include_once($path)) {
				return TRUE;
			} else {
				throw new importException();
			}
		} else {
			throw new importException();
		}
	}


	private function parse_lib($dir = array())
	{
		
	}

	private function include_ini($dir = array())
	{
		$path = ROOT;
		foreach($dir as $element) {
			$path .= DS.$element;
		}
		$path .= '.ini';
		
		return parse_ini_file($path, TRUE, INI_SCANNER_RAW);
	}

	private function parse_mod($dir = array())
	{
		
	}

	private function parse_src($dir = array())
	{
		
	}

	private function parse_tpl($dir = array())
	{
		$file = $dir[count($dir)-1].'.html';
		$path = DS;
		
		for($i=1;$i<count($dir)-1;$i++) {
			$path .= $dir[$i].DS;
		}

		$config = Config::get_instance('look_and_feel');
		$pri = ROOT.DS.'templates'.DS.$config->get('template').$path.$file;
		$sec = ROOT.DS.'templates'.DS.'system'.$path.$file;
		$tri = ROOT.$path.'views'.DS.'tpl'.DS.$file;
		
		if(file_exists($pri)) {
			include_once($pri);
			return TRUE;
		} else if(file_exists($sec)) {
			include_once($sec);
			return TRUE;
		} else if(file_exists($tri)) {
			include_once($tri);
			return TRUE;
		} else {
			throw new importException();
		}
	}


	private function include_r($dir, $r = FALSE)
	{
		if(!is_dir($dir))
			return FALSE;
		$dh = opendir($dir);
		while(FALSE !== ($file = readdir($dh))) {
			if($file[0] != ".") {
				if(is_file("$dir/$file"))
					include_once("$dir/$file");
				elseif(is_dir("$dir/$file") && $r == TRUE)
					$this->include_r("$dir/$file");
			}
		}
		closedir($dh);
	}

}
/**
 * EOF /
 */