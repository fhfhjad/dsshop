<?php

class AssignmentModel extends RelationModel{
	protected $_validate = array(
		array('bid','number','有误！'),
		array('coefficient','number','系数有误！'),
		array('subscribe','number','每份认购金额有误！'),
	);
	
	protected $_link=array(
		'borrowing'=> array(  
			'mapping_type'=>BELONGS_TO,
			'class_name'=>'borrowing',
            'foreign_key'=>'bid',
            'mapping_name'=>'borrowing',
		),
	);
	
}
?>