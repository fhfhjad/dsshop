<?php

class RechargeModel extends RelationModel{
	protected $_validate = array(
				array('proving','require','验证码必须！'), 
				array('proving','checkCode','验证码错误!',0,'callback',1),
				array('oid','number','充值类型有误！',2),
				array('number','number','流水号有误！',2),
				array('money','number','充值金额有误！'),
		);
	protected function checkCode($code){
		if(md5($code)!=session('verify')){
			return false;
		}else{
			return true;
		}
	}
	protected $_link=array(
		'user'=> array(  
			'mapping_type'=>BELONGS_TO,
			'class_name'=>'user',
            'foreign_key'=>'uid',
            'mapping_name'=>'user',
			'mapping_fields'=>'username',
			'as_fields'=>'username:username',
		),
	);
}
?>