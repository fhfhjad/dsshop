<?php

class Article_addModel extends Model{

	protected $_auto=array(

		array('modified','time',1,'function'),

	);  

   

/*	  public   function _after_insert(&$data,$options){
			
		
         $mod = D("Article");
		 $da['fid']=$data['id'];
		 $field = $mod->getDbFields();
		 foreach($_POST as $k=>$v){
			 if(in_array($k,$field)){
				 $da[$k]=$v;
			 }
		 }

		 $mod->add($da);
		
		 

	}*/

	
}

?>
