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
		$c = $this->db->select(
					array('COUNT(*)'),
					'WHERE published = 1'
				);
		$vars = array(	'post_id',
				'title',
				'alias',
				'text',
				'author',
				'pubdate',
				'category_id'
			);
		$db = $this->db->select(
					$vars,
					'WHERE published = 1',
					'pubdate DESC',5
				);

		if($c['COUNT(*)'] == 0)
			return false;

		if($c['COUNT(*)'] == 1)
			$db = array($db);

		$posts = array();
		foreach ($db as $tmp) {
			$post['title'] = $tmp['title'];
			$post['url'] = $this->uri->format_URL(
				'blag/post/'.$tmp['alias']);
			$post['author_profile'] = $this->uri->format_URL(
				'user/profile/'.$tmp['author']);
			$this->db->table = 'users';
			$u = $this->db->select(array('username'),
				'WHERE user_id = '.$tmp['author']);
			$post['author'] = $u['username'];
			$this->db->table = 'categories';
			$c = $this->db->select(array('title','alias'),
				'WHERE category_id = '.$tmp['category_id']);
			$post['category'] = $c['title'];
			$post['category_url'] = $this->uri->format_URL(
				'blag/category/'.$c['alias']);
			$post['date_iso'] = date('c',$tmp['pubdate']);
			$post['date'] = date('r',$tmp['pubdate']);
			$post['summary'] = $this->summary($tmp['text']);
			$posts[] = $post;
		}



		return $posts;
	}
	private function count_pages($type)
	{
		switch($type) {
			case 'tag':

				break;
			case 'category':

				break;
			case 'page':
			default:
				
				break;
		}
	}
	public function page_next()
	{
		$uri = $this->uri->parse_URL('b/m/page');
		$page = $uri['page'];
		if(empty($page) || !is_int($page))
			return false;

		$pages = $this->count_pages($uri['m']);


		return false;
	}
	public function page_prev()
	{
		return false;
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
