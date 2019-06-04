<?php

class Auction_listModel extends RelationModel{
	protected $_link=array(
		'user'=> array(  
			'mapping_type'=>BELONGS_TO,
			'class_name'=>'user',
            'foreign_key'=>'uid',
            'mapping_name'=>'user',
			'mapping_fields'=>'username',
			'as_fields'=>'username:username',
		),
		'auction'=> array(  
			'mapping_type'=>BELONGS_TO,
			'class_name'=>'auction',
            'foreign_key'=>'aid',
            'mapping_name'=>'auction',
		),
	);
}
?>