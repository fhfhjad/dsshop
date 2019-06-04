<?php

class CollectionModel extends RelationModel{
	protected $_link=array(
		'borrowing'=> array(  
			'mapping_type'=>BELONGS_TO,
			'class_name'=>'borrowing',
            'foreign_key'=>'bid',
            'mapping_name'=>'borrowing',
			'mapping_fields'=>'title,type,candra,way',
			'as_fields'=>'title:title,type:types,candra:candra,way:boway',
		),
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