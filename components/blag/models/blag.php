<?php
namespace MagicPotion;

abstract class BlagModel extends Model {
	public function get_username($id)
	{
		$this->db->table = 'users';
		$vars = array('username');
		$condition = "WHERE user_id = $id";
		$db = $this->db->select($vars, $condition);
		return $db['username'];
	}
	function category_list($mode,$count = FALSE)
	{
		$vars = array ('id','title','alias');
		$this->db->table = 'categories';
		$db = $this->db->select($vars,FALSE,'id ASC');
		foreach($db as $cat) {
			if($mode == 'list') {
				$tags .= '<li>';
			} else if($mode == 'select') {
				$tags .= '<option value="'.$cat['id'].'">';
			}

			if($mode == 'select') {
				$tags .= $cat['title'];
			} else {
				$tags .= '<a href="'.
					$this->uri->format_URL('news',
						'category', $cat['alias'])
					.'">'.$cat['title'].'</a>';
			}
			if($count){
				$this->db->table = 'posts';
				$time = time();
				$count = $this->db->select(
					array('COUNT(*) AS posts'),
					"WHERE category = {$cat['id']}
					 AND published = 1
					 AND pubdate < {$time}");

				$tags .= '('.$count['posts'].')';
			}
			if($mode == 'list') {
				$tags .= '</li>';
			} else if($mode == 'select') {
				$tags .= '</option>';
			} else if($mode == 'cloud') {
				$tags .= ' ';
			} else {
				$tags .= '<br />';
			}
			$tags .= "\n";
		}

		return $tags;
	}
}

/**
 * EOF /components/blag/model/blag.php
 */
