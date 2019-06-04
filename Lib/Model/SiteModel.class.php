<?php

class SiteModel extends  RelationModel{

//  protected $_validate = array (
//	  array ('', 'require', 'Firstname is required!' ),
//  );
	protected $_auto=array(

	array('addtime','time',1,'function'),

	);

	protected $_link = array(
	   'site_add'=> array(  
          'mapping_type'=>BELONGS_TO,
          'class_name'=>'site_add',
          'foreign_key'=>'aid',
          'as_fields'=>'litpic,model,uptime,content',
	  ),
    );


	
	
	
	public function _after_update($data,$options){
		if(isset($_REQUEST['aid'])){
			
		   $mod = D("Site_add");
           if($create=$mod->create()){
			   $mod->where('id="'.$_REQUEST['aid'].'"')->save();
		   }
		}

	}


	
  	

	

	

	
}

?>
