<?php
/**
 * Magic Potion.
 *
 * Enables pretty URLs
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2010, http://web.mirakulix.org/
 */
namespace MagicPotion;

class Rewrite {
	private $format;
	private $config;

	public function __construct($format = 'component/model/query')
	{
		$this->format = $format;
		$this->config = Config::get_instance('rewrite');
	}

	public function alias($str)
	{
		$str = strtolower($str);
		$str = str_replace(' ', '-', $str);
		$str = preg_replace('/[^a-z0-9|\.|\-|_]/','',$str);
		$words = explode('-',$str);
		$str = substr($str, 0, $this->config->get('alias_length'));
		if(count($words) >= 3) {
			$word = strrchr($str, '-');
			if(!in_array(str_replace('-', '', $word), $words)) {
				$end = strrpos($str, $word);
				$str = substr($str, 0, $end);
			}
		}
		return $str;
	}

	public function parse_URL($url_format = NULL)
	{
		if(!$url_format)
			$url_format = $this->config->get('url_format');
		$format = explode('/',$url_format);
		$element = explode('/',$this->request_URI(),count($format));

		for($i=0;$i<count($format);$i++)
			if(isset($element[$i]))
				$data[$format[$i]] = strtolower($element[$i]);

		return $data;
	}

	public function format_URL($url, $format = FALSE)
	{
		$this->config = Config::get_instance('rewrite');
		if(!$format)
			$format = explode('/',
				$this->config->get('url_format'));
		
		$data = '';
		if(is_array($url)) {
			foreach($format as $element) {
				$data .= '/'.$url[$element];
			}
			if(isset($url['anchor']))
				$data .= '#'.$anchor;
		} else {
			$data .= '/'.$url;
		}

		
		if($this->config->get('rewrite') == TRUE)
			return BASEPATH.$data;
		else
			return BASEPATH.'/index.php'.$data;
	}

	public function request_URI()
	{
		$uri = str_replace(BASEPATH.'/','',$_SERVER['REQUEST_URI']);
		$tmp = explode('/',$uri,2);
		if($tmp[0] == 'index.php')
			$uri = str_replace('index.php/', '', $uri);

		return $uri;
	}
}
/**
 * EOF /includes/rewrite.php
 */
