<?php
namespace MagicPotion;

class importException extends \Exception {}

#class Import extends Object {
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
				$this->parse_path($dir, '.php');
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
			$path .= '/'.$element;
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
			$path .= '/'.$element;
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
		$path = '/';
		
		for($i=1;$i<count($dir)-1;$i++) {
			$path .= $dir[$i].'/';
		}

		$config = Config::get_instance('look_and_feel');
		$pri = ROOT.'/templates/'.$config->get('template').$path.$file;
		$sec = ROOT.'/templates/system'.$path.$file;
		$tri = ROOT.$path.'views/tpl/'.$file;
		
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
 * EOF /system/import.php
 */