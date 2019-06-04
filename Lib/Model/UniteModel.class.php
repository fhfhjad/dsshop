<?php

class UniteModel extends RelationModel{
	protected $_validate = array(
		array('name','require','联动名有误！'),
		array('value','require','联动值有误！'),
		array('state','number','状态有误！'),
		array('pid','number','所属联动有误！'),
		array('order','number','排序有误！'),
	);
}
?>