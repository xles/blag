<?php
/**
 * Magic Potion.
 *
 * Class for database management.
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	db
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2010, http://web.mirakulix.org/
 */
namespace MagicPotion;

/**
 *
 */
interface iDatabase {
	function __construct();
	function insert($sql);
	function select($sql);
	function update($sql);
	function delete($sql);
/*	function query($sql, $values); */
	function sanitize($str,$action = FALSE);
	function insert_id();
	function __destruct();
}

/**
 *
 */
class Database {
	public $table;
	public $db;
	private $logger, $config;
	
/**
 *
 */
	function __construct()
	{
		$this->config = Config::get_instance('database');
		Helper::import('inc.database.'.$this->config->get('vendor'));

		$this->logger = Logger::get_instance();
		$database = __NAMESPACE__.'\\Database\\'
			.ucfirst($this->config->get('vendor'));
		$this->db = new $database();
	}
	
/**
 *
 * @param <type> $data
 * @return <type>
 */
	function insert($data)
	{
		foreach($data as $var => $val){ 
			$vars .= ','.$var;
			if(is_numeric($val))
				$vals .= ','.$val;
			else
				$vals .= ','.$this->db->sanitize($val,'insert');
		} 
		$vals = ltrim($vals,',');
		$vars = ltrim($vars,',');
		$query = "INSERT INTO ".$this->config->get('prefix')
			.$this->table." ($vars) VALUES ($vals);";
		echo $query;
		return $this->db->insert($query);
	}
	
/**
 *
 * @param <type> $vars
 * @param <type> $condition
 * @param string $order
 * @param string $limit
 * @return <type>
 */
	function select($vars = FALSE, $condition = FALSE,
		$order = FALSE, $limit = FALSE)
	{
		if(!$condition)
			$condition = 'WHERE 1';
		else if(is_numeric($condition))
			$condition = 'WHERE id = '.$condition;
		
		if($limit)
			$limit = 'LIMIT '.$limit;
			
		if($order)
			$order = 'ORDER BY '.$order;
			
		if(!$vars) {
			$vars = '*';
		} else {
			$tmp = '';
			foreach($vars as $var) {
				$tmp .= ','.$this->db->sanitize($var);
			}
			$vars = ltrim($tmp,',');
		}
				
		$query = "SELECT $vars FROM ".$this->config->get('prefix')
			.$this->table." $condition $order $limit;";

		return $this->db->select($query);
	}
	
/**
 *
 * @param <type> $values
 * @param string $condition
 * @return <type>
 */
	function update($values,$condition)
	{
		if(is_numeric($condition))
			$condition = 'WHERE id = '.$condition.' LIMIT 1';
		
		foreach($values as $var => $val) {
			$vars[] = $var;
			if(is_numeric($val))
				$vals[] = $val;
			else
				$vals[] = $this->db->sanitize($val,'insert');
		}
		
		$tmp='';
		for($i=0;$i<count($vars);$i++) {
			$tmp .= ', '.$vars[$i].' = '.$vals[$i];
		}
		$vars = ltrim($tmp,',');
		
		$query = "UPDATE ".$this->config->get('prefix')
			.$this->table." SET $vars $condition;";
		return $this->db->update($query);
	}
	
/**
 *
 * @param string $condition
 * @return <type>
 */
	function delete($condition)
	{
		if(is_numeric($condition))
			$condition = 'WHERE id = '.$condition.' LIMIT 1';
		$query = "DELETE FROM ".$this->config->get('prefix')
			.$this->table." $$this->db->sanitize($condition);";
		return $this->db->delete($query);
	}
	
/**
 *
 * @param <type> $sql
 * @return <type>
 */
	function query($sql)
	{
		return $this->db->query($sql,$this->table);
	}
/**
 *
 * @return <type>
 */
	function insert_id()
	{
		return $this->db->insert_id();
	}
	
/**
 *
 */
	function __destruct()
	{
	
	}
}
/**
 * EOF /inc/db.php
 */
