<?php

class Raise_listModel extends RelationModel{
	protected $_link=array(
		'user'=> array(  
			'mapping_type'=>BELONGS_TO,
			'class_name'=>'user',
            'foreign_key'=>'uid',
            'mapping_name'=>'user',
			'mapping_fields'=>'username',
			'as_fields'=>'username:username',
		),
		'raise'=> array(  
			'mapping_type'=>BELONGS_TO,
			'class_name'=>'raise',
            'foreign_key'=>'rid',
            'mapping_name'=>'raise',
		),
	);
}
?>