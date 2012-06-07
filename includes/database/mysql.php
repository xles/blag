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
namespace MagicPotion\Database;


class mysqlException extends \MagicPotion\Error {}

/***   blubb   ***/
class Mysql implements \MagicPotion\iDatabase {

/***   blubb   ***/
	public $table;
	private $config;
	private $logger;
	
/***   blubb   ***/
	function __construct()
	{
		$this->config = \MagicPotion\Config::get_instance('database');
		$this->logger = \MagicPotion\Logger::get_instance();
		
		if(mysql_connect($this->config->get('host'), 
				$this->config->get('username'), 
				$this->config->get('password'))) {
			if(mysql_select_db($this->config->get('database'))) {
				return TRUE;
			} else {
				return mysql_error();
			}
		} else {
			return mysql_error();
		}
	}
	
/***   blubb   ***/
	function insert($sql)
	{
		$this->logger->log_var($sql, '$sql');
		if(!$c = mysql_query($sql)) {
			return mysql_error();
		} else {
			return mysql_affected_rows();
		}
	}
	
/***   blubb   ***/
	function select($sql)
	{
		$this->logger->log_var($sql, '$sql');
		if(!$c = mysql_query($sql)) {
			return mysql_error();
		} else {
			if(mysql_num_rows($c) > 1) {
				while ($row = mysql_fetch_assoc($c)) {
					$tmp[] = $row;
				}
				return $tmp;
			} else if(mysql_num_rows($c) == 0) {
				return FALSE;
			} else {
				return mysql_fetch_assoc($c);
			}
		}	
	}
	
/***   blubb   ***/
	function update($sql)
	{
		$this->logger->log_var($sql, '$sql');
		if(!mysql_query($sql)) {
			return mysql_error();
		} else {
			return mysql_affected_rows();
		}
	}
	
/***   blubb   ***/
	function delete($sql)
	{
		$this->logger->log_var($sql, '$sql');
		if(mysql_query($sql)) {
			return mysql_error();
		} else {
			return mysql_affected_rows();
		}
	}
	
/***   blubb   ***/
/*	function query($sql, $table)
	{
		if(!$c = mysql_query($sql)) {
			return mysql_error();
		} else {
			return mysql_affected_rows($c);
		}
	}
*/	
/***   blubb   ***/
	function sanitize($str,$action = FALSE)
	{
		if($action == 'insert' || $action == 'update') {
			$str = bin2hex($str);
			$str = '0x'.$str;
			return $str;
		} else {
			return mysql_real_escape_string($str);
		}
	}
	
	function insert_id()
	{
		return mysql_insert_id();
	}
	
/***   blubb   ***/
	function __destruct()
	{
		//parent::__destruct();
		/* Connect to MySQL server? */
	}
}
/**
 * EOF /inc/db/mysql.php
 */
