<?php

class LinesModel extends RelationModel{
	protected $_validate = array(
		array('assure','number','额度有误！'),
	);
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