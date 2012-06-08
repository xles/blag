<?php
namespace MagicPotion;

class BlagPostModel extends BlagModel {
	public function put_post($vars)
	{

	}
	public function get_post($id)
	{
		$this->db->table = 'posts';
		$vars = array('body');
		$condition = "WHERE user_id = $id";
		$db = $this->db->select($vars, $condition);
		return $db['username'];
	}
	public function rm_post($id)
	{

	}
	public function update_post($id)
	{

	}	
}

/**
 * EOF /components/blag/models/post.php
 */
