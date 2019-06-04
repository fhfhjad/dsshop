<?php

class OnlineModel extends Model{
	protected $_validate = array(
				array('pid','require','合作者ID有误！'),
                array('checking','require','密钥有误！'),
				array('account','require','收款账户有误！',2),
				array('introduce','require','介绍有误！'),
				array('state','number','状态有误！'),
				array('order','number','排序有误！'),
		);
}
?>