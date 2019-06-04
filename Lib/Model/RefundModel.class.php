<?php

class RefundModel extends RelationModel{
	protected $_link=array(
		'borrowing'=> array(  
			'mapping_type'=>BELONGS_TO,
			'class_name'=>'borrowing',
            'foreign_key'=>'bid',
            'mapping_name'=>'borrowing',
			'mapping_fields'=>'title,type,rates,money',
			'as_fields'=>'title:title,type:types,rates:rates,money:moneys',
		),
		'user'=> array(  
			'mapping_type'=>BELONGS_TO,
			'class_name'=>'user',
            'foreign_key'=>'uid',
            'mapping_name'=>'user',
			'mapping_fields'=>'username,autorefund',
			'as_fields'=>'username:username,autorefund:autorefund',
		),
	);
}
?>