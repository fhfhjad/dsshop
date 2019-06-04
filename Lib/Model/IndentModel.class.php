<?php

class IndentModel extends RelationModel{
	protected $_link=array(
		'erector'=> array(  
			'mapping_type'=>BELONGS_TO,
			'class_name'=>'erector',
            'foreign_key'=>'eid',
            'mapping_name'=>'erector'
		),
	);
}
?>