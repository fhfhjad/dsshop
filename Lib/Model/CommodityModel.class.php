<?php

class GoodsModel extends RelationModel{
	protected $_validate = array(
		array('title','require','标题有误！'),
		array('number','require','编号有误！'),
		array('zimg','require','主图有误！'),
		array('fid','number','类目有误！'),
		array('sort','number','排序有误！'),
		array('details','require','内容有误！'),
		array('specifications','require','内容有误！'),
		array('img','require','细节图有误！'),
		
	);
}
?>