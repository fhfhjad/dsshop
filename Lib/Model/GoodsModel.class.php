<?php

class GoodsModel extends RelationModel{
	protected $_validate = array(
		array('title','require','标题有误！'),
		array('number','require','编号有误！'),
		array('fid','number','类目有误！'),
		array('sort','number','排序有误！'),
		array('details','require','内容有误！'),
		array('specifications','require','规格有误！'),
	);
	protected $_link=array(
		'goodslist'=> array(  
			'mapping_type'=>BELONGS_TO,
			'class_name'=>'goodslist',
            'foreign_key'=>'fid',
            'mapping_name'=>'goodslist',
			'mapping_fields'=>'title',
			'as_fields'=>'title:fname',
		),
	);
}
?>