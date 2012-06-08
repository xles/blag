<?php
// <editor-fold defaultstate="collapsed" desc="Header DocBlock">
/**
 * Magic Potion, logging.php
 *
 * 
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	logging
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2011
 * @link	http://web.mirakulix.org/
 */
// </editor-fold>
namespace MagicPotion;

/**
 * 
 */
class Logger {

	private $buffer = array();
	private $errs = array();
	private $lastlog = FALSE;

	private $method;

	private $file;

	static public function &get_instance()
	{
		static $instance;
		if (!is_object($instance)) {
			$instance = new Logger();
		}

		return $instance;
	}
	
	public function set_file($str)
	{
		if(DEBUG) {
			$this->buffer[] = NL.'--- '.$str.' ---';
			$this->file = $str;
		}
	}


	public function set_method($str)
	{
		if(DEBUG) {
//			$this->buffer[] = "\t".$str."\n";
			$this->method = $str;
		}
	}


	public function log_var($vars, $msg)
	{
		if(DEBUG) {
			$backtrace = debug_backtrace();
			$d = $backtrace[1];
			$line = $d['class'].$d['type'].$d['function'];
			$msg = $line.': Dumping '.$msg.':';
			$this->buffer[] = NL.$this->gettime().$msg;
			$this->buffer[] = NL.print_r($vars, TRUE);
		}
	}
	
	
	public function log_error($str)
	{
		if(DEBUG) {
//			$this->errs[] = NL.$str;
			$this->buffer[] = NL.$this->gettime().$str;
		}
	}

	private function gettime()
	{
		$utime = round(microtime(),4);
		$utime = (string) date('s')+$utime;
		$h = date('H'); $m = date('i');
//		$d = new DateTime($time, $object);
//		return date('H:i:s.u').$utime.' ';
		return sprintf('%02d:%02d:%02.4f > ', $h, $m, $utime);
	}

	public function log_msg($str)
	{
		if(DEBUG) {
			$this->lastlog = NL.$this->gettime().
				'--- '.$str.'. ---';
			$this->buffer[] = $this->lastlog;
		}
	}

	
	public function log_line($str)
	{
		if(DEBUG) {
			$backtrace = debug_backtrace();
			$d = $backtrace[1];
			$line = $d['class'].$d['type'].$d['function'];
			$this->lastlog = NL.$this->gettime().$line.': '.$str.'.';
			$this->buffer[] = $this->lastlog;
		}
	}

	public function log_state($state)
	{
		if(DEBUG) {
			if($this->lastlog != end($this->buffer)) {
				$this->buffer[] = $this->lastlog;
				unset($this->lastlog);
			}
			$this->buffer[] = '.. '.$state;
		}
	}


	public function log_ok()
	{
		if(DEBUG)
			$this->log_state('OK');
	}


	public function log_fail()
	{
		if(DEBUG)
			$this->log_state('FAILED');
	}


	public function end_file($str)
	{
		if(DEBUG) {
			$this->buffer[] = NL.'--- EOF: '.$str.' ---';
		}
		return FALSE;
	}


	public function debugger($echo = TRUE)
	{
		if(DEBUG) {
			$data = "<pre>\nDebugger:";
			foreach ($this->buffer as $line) {
				$data .= htmlentities($line);
			}
			foreach ($this->errs as $line) {
				$data .= htmlentities($line);
			}
			$data .= '</pre>';
		}
		if($echo)
			echo $data;
		else
			return $data;
	}

}
class Debugger extends Logger {
}

/**
 * EOF /includes/logger.php
 */
