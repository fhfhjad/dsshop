<?php

class Auth_groupModel extends  RelationModel{
	protected $_validate = array(
		array('title','require','分组名必须！'),
		array('fid','require','分组必须！'),
	);
	public function _after_delete($data,$options){

		$id=intval($_REQUEST['id']);
		$mod = D("Auth_group_access");
		$mod->where('group_id="'.$id.'"')->delete();
	}
	

	
}

?>
