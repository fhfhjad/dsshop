<?php

class Membership_gradeModel extends Model{
	protected $_validate = array(
                array('name','/^[\w\x{4e00}-\x{9fa5}]+$/u','积分名称名称有误！'),
				array('img','require','图片必须！'),
				array('min','number','最小值有误！'),
				array('max','number','最大值有误！'),
		);
}
?>