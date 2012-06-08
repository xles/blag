<?php
// <editor-fold defaultstate="collapsed" desc="Header DocBlock">
/**
 * Magic Potion, template.php
 *
 * {normal_tag}
 * {=CONSTANT}
 * {@language_tag}
 * {~make_link}
 * {#module_template_file}
 * {/template/file}
 * {:if condition}
 * {:else}
 * {:endif}
 * {:loop var}
 * {:endloop}
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	template
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2011
 * @link	http://web.mirakulix.org/
 */
// </editor-fold>
namespace MagicPotion;

/**
 * 
 */
class Template {
	public $lang;

	private $vars = array();

	private $buffer;
	private $logger;
	private $uri;

	public function __construct($file,$vars = array())
	{
		$this->logger = Logger::get_instance();
		$this->vars = $vars;
		
		$this->get_file($file);
		
		$this->lang = new Locale();
		$this->uri = new Rewrite();
	}
	
	public function set_component($component, $section = FALSE)
	{
		$this->lang->set_component($component, $section);
	}

	public function set_tag($var,$val)
	{
		unset($this->vars[$var]);
		$this->vars[$var] = $val;
	}

	public function get_tag($var)
	{
		if(isset($this->vars[$var]))
			return $this->vars[$var];
		else
			return FALSE;
	}

	private function parse_tags($str)
	{
		switch($str[1][0]) {
			case '~':
				/* stuff is a link. */
				$data = $this->tag_link($str[1]);
				break;
			case '=':
				/* stuff is a constant. */
				$data = $this->tag_constant($str[1]);
				break;
			case '@':
				/* stuff is a call to Locale->line. */
				$data = $this->tag_language($str[1]);
				break;
			case '#':
				/* stuff is a module template file. */
				$data = $this->tag_module($str[1]);
				break;
			case '/':
				/* stuff is an absolute template file path. */
				$data = $this->tag_template($str[1]);
				break;
			default:
				/* stuff is a normal tag. */
				$data = $this->tag_variable($str[1]);
				break;
		}
		
		return $data;
	}
	private function tag_variable($str)
	{
		if(isset($this->vars[$str]))
			return $this->vars[$str];
		else
			return '{'.$str.'}';
	}
	private function tag_template($str)
	{
		if(isset($this->vars[$str]))
			$vars = $this->vars[$str];
		else
			$vars = '';
		$tpl = new Template($str, $vars);

		return $tpl->output();
	}
	private function tag_module($str)
	{
		$str = str_replace('#', '', $str);
//		$module = '/templates/'.TEMPLATE.'modules/';
//		$var = new Template($file, $vars);
		$module = $this->get_module($str);
		$module->load();
	}
	private function tag_language($str)
	{
		$str = str_replace('@', '', $str);
		return $this->lang->line($str);
	}

	private function tag_constant($str)
	{
		$str = str_replace('=', '', $str);
		if(defined(strtoupper($str)))
			return constant(strtoupper($str));
		else 
			return '{='.$str.'}';
	}

	private function tag_link($str)
	{
		$str = str_replace('~', '', $str);
		return $this->uri->format_URL($str);
		
	}

	/* Fix me. */
	private function tag_if($str)
	{
		$cond = explode(' ',$str[1]);
		$str = explode('{:else}', $str[2]);

		$true = $str[0];
		if(count($str) > 1) {
			$false = $str[1];
		} else {
			$false = '';
		}

		if(count($cond) == 1) {
			if(isset($this->vars[$cond[0]]) && 
				!empty($this->vars[$cond[0]])) {
				$data = $true;
			} else {
				$data = $false;
			}
		} else if(count($cond) == 3) {
			$cond[0] = $this->vars[$cond[0]];
			switch($cond[1]) {
				case '=':
				case '==':
					if($cond[0] == $cond[2])
						$data = $true;
					else
						$data = $false;
					break;
				case '!=':
					if($cond[0] != $cond[2])
						$data = $true;
					else
						$data = $false;
					break;
				case '>':
				case '>=':
					if($cond[0] >= $cond[2])
						$data = $true;
					else
						$data = $false;
					break;
				case '<':
				case '<=':
					if($cond[0] <= $cond[2])
						$data = $true;
					else
						$data = $false;
					break;
			}
		} else {
			$data = $true.$false;
		}
		return $data;
	}

	/* Fix me. */
	private function tag_loop($str)
	{
		$vars = $this->vars[$str[1]];
		$loop = $str[1];
		$str = $str[2];
		$data = '';
		foreach($vars as $var) {
			foreach($var as $var => $val) {
				if(empty($val))
					$val = '';
				$this->set_tag($loop.'.'.$var, $val);
			}
			$data .= $this->find_tags($str);
		}
		return $data;
	}
	private function find_tags($str)
	{
		$this->logger->log_line('Parsing tags');

		/**
		 * Remove C-style comments...
		 */
		$str = preg_replace('/\/\*(.*?)\*\//ism', '', $str);

		/**
		 * Parse conditional tags.
		 */
		$callback = array($this,'tag_if');
		$pattern = '/\{\:if\s(.*?)\}(.*?)\{\:endif\}/ism';
		$str = preg_replace_callback($pattern,$callback,$str);

		/**
		 * Parse loop tags.
		 */
		$callback = array($this,'tag_loop');
		$pattern = '/\{\:loop\s(.*?)\}(.*?)\{\:endloop\}/ism';
		$str = preg_replace_callback($pattern,$callback,$str);

		/**
		 * Parse other tags
		 */
		$str = preg_replace_callback ('/\{(.*?)\}/ism',
			array($this, 'parse_tags'),$str);

		$this->logger->log_line('Tags parsed');

		return $str;
	}

	
	
	private function get_file($file)
	{
		$this->logger->log_line('Loading '.$file.' to buffer');
		$str = 'tpl'.str_replace('/', '.', $file);

		try {
			ob_start();
			import($str);
			$buffer = ob_get_contents();
			ob_end_clean();
			$this->buffer = $buffer;
			$this->logger->log_ok();
		} catch(importException $e) {
			$this->logger->log_fail();
		}
	}
	public function output()
	{
		$this->buffer = $this->find_tags($this->buffer);
		$this->logger->log_line('Returned template buffer');
		return $this->buffer;
	}
	public function render()
	{
		$this->buffer = $this->find_tags($this->buffer);
		$this->logger->log_line('Printed template buffer');
		if(print($this->buffer))
			return TRUE;
		else
			return FALSE;
	}
}
/**
 * EOF /
 */