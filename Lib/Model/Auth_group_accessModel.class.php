<?php

class Auth_group_accessModel extends  RelationModel{
	protected $_link = array(
	   'admin'=> array(  
          'mapping_type'=>BELONGS_TO,
          'class_name'=>'admin',
          'foreign_key'=>'uid',
          'as_fields'=>'username,email,id,time',
	  ),


    );
	
  	

	
/*    public function _after_insert(&$data,$options){
         $mod = D("Site_add");
		 $field = $mod->getDbFields();
		 $da['sid']=$data['id'];
		 foreach($_POST as $k=>$v){
			 if(in_array($field)){
				 $da[$k]=$v;
			 }
		 }
		 $mod->add($da);
		 

	}*/
	

	
}

?>
