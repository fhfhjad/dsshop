<?php

class ArticleModel extends  RelationModel{

  protected $_validate = array (
	 // array('title','','已有同名文章！',0,'unique',1),
  );
	protected $_auto=array(

	array('addtime','time','','function',1),

	);

	protected $_link = array(
	   'Article_add'=> array(  
          'mapping_type'=>BELONGS_TO,
          'class_name'=>'Article_add',
          'foreign_key'=>'fid',
          'as_fields'=>'litpic,modified,modified_by,hits,Integration,comment',
	  ),
	  'site'=>array(
          'mapping_type'=>BELONGS_TO,
          'class_name'=>'site',
          'foreign_key'=>'catid',
          'as_fields'=>'page_tpl,content_tpl,list_tpl',	  
	  ),
    );


	
	
	
	public function _after_update($data,$options){
		if(isset($_REQUEST['fid'])){
		   $mod = D("Article_add");
           if($mod->create()){
			   $mod->save();
		   }
		}

	}


	
  	

	

	

	
}

?>
