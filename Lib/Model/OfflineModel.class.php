<?php

class OfflineModel extends Model{
	protected $_validate = array(
				array('type','number','充值类型有误！'),
                array('bank_name','/^[\w\x{4e00}-\x{9fa5}]+$/u','开户支行名称有误！'),
				array('name','/^[\w\x{4e00}-\x{9fa5}]+$/u','收款人称有误！'),
				array('bank','number','银行账户有误！'),
				array('order','number','排序有误！'),
		);
}
?>