<?php

class WithdrawalModel extends RelationModel{
	protected $_validate = array(
		array('proving','require','验证码必须！'), 
		array('proving','checkCode','验证码错误!',0,'callback',1),
		array('money','/^\d{1,}\.{0,1}\d{0,2}$/','提现金额有误！'),
		array('type','number','参数有误！'),
		array('wid','number','提现银行必须！'),
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