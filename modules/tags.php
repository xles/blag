<?php
namespace MagicPotion;

class Tags extends Object {
	public function get_cloud() {

	}
	private function tag_list($mode,$count = FALSE)
	{
		$vars = array ('id','title','alias');
		$this->db->table = 'tags';
		$db = $this->db->select($vars,FALSE,'alias ASC');
		foreach($db as $tag) {
			if($mode == 'list') {
				$tags .= '<li>';
			}
			$tags .= '<a href="'.$this->uri->format_URL(
					'news', 'tag', $tag['alias'])
				.'">'.$tag['title'].'</a>';
			if($count){
				$prefix = DB_PREFIX; $time = time();
				$this->db->table = 'post_tags';
				$sql = "SELECT COUNT(*) AS tag_posts
					FROM {$prefix}post_tags
					INNER JOIN {$prefix}posts
					ON {$prefix}post_tags.post={$prefix}posts.id
					WHERE {$prefix}post_tags.tag = {$tag['id']}
					AND {$prefix}posts.published = 1
					AND {$prefix}posts.pubdate < {$time};";

				$count = $this->db->select(
					array('COUNT(*) AS tags'),
					"WHERE tag = {$tag['id']}");

				$tags .= '('.$count['tags'].')';
			}
			if($mode == 'list') {
				$tags .= '</li>';
			} else if($mode == 'cloud') {
				$tags .= ' ';
			} else {
				$tags .= '<br />';
			}
		}

		return $tags;
	}	
	public function set_tags($tags,$pid)
	{
		$tags = explode(',',$tags);


		foreach($tags as $tag) {
			$tag = trim($tag);
			$this->db->table = 'tags';
			$db = $this->db->select(array('id'),
				"WHERE title = '{$tag}'");
			$tid = $db['id'];
			if(!$tid) {
				$vars = array ('title' => $tag,
					'alias' => $this->uri->alias($tag));
				$this->db->insert($vars);
				$tid = $this->db->insert_id();
			}
			$vars = array ( 'tag' => $tid,
					'post' => $pid);
			$this->db->table = 'post_tags';
			$this->db->insert($vars);
//			$tid = $this->db->insert_id();
		}
	}
}

/**
 * EOF /modules/comments.php
 */
