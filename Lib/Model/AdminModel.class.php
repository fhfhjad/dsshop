<?php

class AdminModel extends  RelationModel{

	protected $_validate = array(
		array('email','email','email有误！'),
		array('username','require','用户必须填写！'),
		array('username','','用户已经存在！',0,'unique',1),

	);
	
	protected $_auto = array ( 
		array('password','adminMd5',3,'callback',1), 
		array('time','time',3,'function'), 

	);
  	

	public function adminMd5($password){
		return MD5(substr(MD5(substr(MD5($password),2,25).C('DS_ENTERPRISE')),10,22).C('DS_EN_ENTERPRISE'));
	}
	
	public function _after_delete($data,$options){

		$id=intval($_REQUEST['id']);
		$mod = D("auth_group_access");
		$mod->where('uid="'.$id.'"')->delete();
	}
	
	
	
}

?>
