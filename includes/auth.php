<?php
/**
 * Magic Potion.
 *
 * MOAR INFOZ!!
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	auth
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2010, http://web.mirakulix.org/
 */
namespace MagicPotion;

class authNeededUserException extends Error {}
class forbiddenUserException extends Error {}

/**#@+
 * User access levels.
 */
define('ACCESS_BANNED',		0);
define('ACCESS_MEMBER',		1);
define('ACCESS_AUTHOR',		2);
define('ACCESS_EDITOR',		3);
define('ACCESS_PUBLISHER',	4);

define('ACCESS_MODERATOR',	5);
define('ACCESS_ADMIN',		6);
define('ACCESS_SUPER_USER',	7);
/**#@-*/

import('lib.phpass.PasswordHash');
/**
 * Auth class
 */
class Auth extends Object {

/**
 * Database object.
 */
	protected $db;
	protected $validate;
	public $uri;
	public $lang;
/**
 * Constructor, connects class to the database upon creation of object.
 */
	public function __construct()
	{
		parent::__construct();
		$this->validate = new Validate();
		$this->uri = new Rewrite();
		$this->db = new Database();
		$this->db->table = 'users';
		$this->lang = new Locale();
		$this->lang->set_component('user');
		
	}

/**
 * Add user.
 */
	public function add_user($data)
	{
		foreach($data as $var => $val){ 
			$$var = trim($val);
			if($var != 'verify') {
				$vars[$var] = trim($val);
			}
			if(empty($val)) {
				$error[] = $this->lang->line('Empty_fields');
			}
		} 

		if ($this->check_username($username)) { 
			$error[] = $this->lang->line('Username_in_use');
		} 

		if (!$this->validate->email($email)) { 
			$error[] = $this->lang->line('Invalid_e-mail');
		} 

		if ($passwd != $verify) {
			$error[] = $this->lang->line('Password_mismatch');
		}

		if (!isset($error)) {
			$vars['passwd'] = md5($passwd);
			$vars['regdate'] = time();
			
			$this->db->insert($vars);
			return 0;
		} else {
			return $error;
		}
	}
	
/**
 * Remove user.
 * 
 * Returns FALSE on success, SQL server error on failure.
 */
	public function remove_user($id)
	{
		if(!$this->check_access(ACCESS_ADMIN)) {
			return $this->lang->line('Authorization_needed');
		}

		$db = $this->db->delete($id);
		if(is_numeric($db)) {
			return FALSE;
		} else {
			return $db;
		}
	}
	
/**
 * Edit user.
 * 
 * Returns FALSE on success, SQL server error on failure.
 */
	public function edit_user($data,$id = FALSE)
	{
		if($id) {
			if(!$this->is_authorized(ACCESS_ADMIN)) {
				$error[] = $this->lang->line('Not_authorized');
			}
		} else {
			$id = $this->session->get('UID');
		}
		$static = array('submit',
				'old_passwd',
				'new_passwd',
				'ver_passwd');
		foreach($data as $var => $val){ 
			if(!empty($val)) {
				$$var = trim($val);
				if(!in_array($var,$static))
					$vars[$var] = trim($val);
			}
		} 
		
		if(isset($old_passwd) || isset($new_passwd) || 
			isset($ver_passwd)) {
			if(empty($old_passwd) || empty($new_passwd) ||
				empty($ver_passwd))
				$error[] = $this->lang->line('Empty_fields');

			if(!$this->check_passwd($old_passwd)) {
				$error[] = $this->lang->line('Wrong_password');
			} else if($new_passwd != $ver_passwd) {
				$error[] = $this->lang->line('Password_mismatch');
			} else {
				$vars['passwd'] = md5($new_passwd);
			}
		}
		
		if(!isset($email) || !isset($email))
			$error[] = $this->lang->line('Empty_fields');

		if (!$this->validate->email($email)) {
			$error[] = $this->lang->line('Invalid_e-mail');
		}

		if (!isset($error)) {
			$db = $this->db->update($vars,$id);
			if(is_numeric($db))
				return FALSE;
			
			return array($db);
		} else {
			return $error;
		}
	}

/**
 * Validate user.
 */
	public function login($user,$passwd,$save = FALSE)
	{
		if(empty($user) || empty($passwd))
			return $this->lang->line('Empty_fields');

		if(!$this->check_username($user))
			$error[] = $this->lang->line('Wrong_username');


		$passwd = md5(trim($passwd));

		$vars = array('user_id','passwd','access');
		$condition = "WHERE username = '$user' LIMIT 1";

		$db = $this->db->select($vars, $condition);

		if($db['access'] == ACCESS_BANNED)
			$this->logout();

		$this->log->log_var($passwd,'$passwd');
		$this->log->log_var($db,'$db');
		
		if($db['passwd'] != $passwd)
			$error[] = $this->lang->line('Wrong_password');
		
		if(isset($error))
			return $error;
			
		$vars = array(
			'lastIP' => $_SERVER['REMOTE_ADDR'],
			'lastlogin' => date('Y-m-d H:i:s')
		);
		$condition = "WHERE user_id = '{$db['user_id']}' LIMIT 1";
		$login = $this->db->update($vars, $condition);

		if(!is_numeric($login))
			return $login;
			
		$this->session->set('UID', $db['user_id']);
		$this->session->set('USERNAME', $user);
		
		$this->log->log_var($_COOKIE,'$_COOKIE');
		
		if($save) {
			$expire = time()+3600*24*30;
			setcookie('SID', session_id(), $expire);
		}
		return FALSE;
	}
	
	public function logout()
	{
		setcookie('SID', '', -3600);
		
		return session_destroy();
	}
	
	
	private function check_username($username)
	{
		$vars = array('COUNT(*)');
		$condition = "WHERE username = '$username'";
		$count = $this->db->select($vars, $condition);

		if ($count['COUNT(*)'] > 0) { 
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function require_login()
	{
		if(!$this->session->get('UID'))
			throw new authNeededUserException();
		else
			return TRUE;
	}
	
	public function is_logged_in()
	{
		if($this->session->get('UID'))
			return TRUE;
		else
			return FALSE;
	}
	
	public function require_access($lvl)
	{
		$this->require_login();
		if($this->check_access($lvl)) {
			return TRUE;
		} else {
			throw new forbiddenUserException();
		}
	}


	public function check_access($access = FALSE, $id = FALSE)
	{
		if(!$this->session->get('UID'))
			return FALSE;
		
		if(!$id)
			$id = $this->session->get('UID');
		
		$cond = 'WHERE user_id = '.$id;
		$level = $this->db->select(array('access'),$cond);
		$this->log->log_var($level,'$level');
		if($access) {
			if ($level['access'] >= $access) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return $level['access'];
		}
	}

	public function bcrypt($str)
	{
		$bcrypt = new \PasswordHash(8,FALSE);
		return $bcrypt->HashPassword($str);
	}

	public function check_passwd($passwd)
	{
		if(!$this->session->get('UID'))
			return FALSE;

		$bcrypt = new PasswordHash(8,FALSE);
		
		$id = $this->session->get('UID');
		$db = $this->db->select(array('passwd'),$id);

		if ($bcrypt->CheckPassword($passwd, $db['passwd'])) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

/*
	public function check_passwd($passwd)
	{
		if(!$this->session->get('UID'))
			return FALSE;
		$id = $this->session->get('UID');
		$db = $this->db->select(array('passwd'),$id);

		if ($db['passwd'] === md5($passwd)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
*/

/**
 * Destructor...
 */
	public function __destruct() 
	{
		/* Hell if I knew... */
	}
}
	
/**
 * EOF /includes/auth.php
 */
