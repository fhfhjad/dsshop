<?php

class IntegralconfModel extends RelationModel{
	protected $_validate = array(
		array('name','require','变量名有误！'),
		array('value','number','积分值有误！'),
		array('pid','number','类别有误！',2),
		array('state','require','说明有误！'),
	);
}
?>