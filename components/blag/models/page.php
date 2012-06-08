<?php
// <editor-fold defaultstate="collapsed" desc="Header DocBlock">
/**
 * Magic Potion, view.php
 *
 * 
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	view
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2011
 * @link	http://web.mirakulix.org/
 */
// </editor-fold>
namespace MagicPotion;	

/**
 * 
 */
class BlagPageModel extends BlagModel {
	public function list_posts()
	{
		$this->db->table = 'posts';
		#$vars = array('letter');
		#$condition = "WHERE hash = '$hash'";
		$db = $this->db->select();
		return $db;
	}
	public function summary($str,$link = FALSE)
	{
		$length = 512;
		if(strlen($str) > $length) {
			$str = substr($str, 0, 512);
			$word = strrchr($str, ' ');
			$end = strrpos($str, $word);
			$str = substr($str, 0, $end);
			if($link)
				return $str.'...<a href="'.$link.'">Read more?</a>';
			else
				return $str.'...';
		} else {
			return $str;
		}
	}
}
final class poop {
	public function get($id)
	{

		$vals = $this->db->select($vars,$id);
		return 0;
	}
	public function save($vars)
	{
		$vals = $this->db->insert($vars);
		return 0;
	}
	public function update($id, $vars)
	{
		$vals = $this->db->update($vars,$id);
		return 0;
	}
	public function publish($id)
	{
		$vals = $this->db->update($vars,$id);
		return 0;
	}
	public function delete($id)
	{
		$vals = $this->db->delete($id);
		return 0;
	}

}
/**
 * EOF /components/blag/models/page.php
 */
