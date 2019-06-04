<?php

class Auth_ruleModel extends  RelationModel{
	protected $_validate = array(
		array('name','require','授权名称有误！'),
		array('condition','require','控制器有误！'),
		array('fid','number','分组必须！'),
	);	
}

?>
