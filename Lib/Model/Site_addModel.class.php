<?php

class Site_addModel extends  RelationModel{

  	protected $_validate = array (
		array('model','require','调用模型有误！',2),
		array('content','require','内容有误！',2),
  	);

 	protected $_auto=array(

		array('uptime','time',1,'function'),
		array('upid','setUpid',3,'callback',1), 
		array('upip','setUpip',3,'callback',1), 
	);  
   
   protected function setUpid(){
	   if($_SESSION['admin_uid']){
		   $id = $_SESSION['admin_uid'];
	   }elseif($_SESSION['user_uid']){
		    $id = $_SESSION['user_uid'];
	   }else{
		   $id = 0;
	   }
	   return $id;
   }
   
   protected function setUpip(){

	   return $_SERVER["REMOTE_ADDR"];
   }   
   
   

	    public function _after_insert(&$data,$options){
         $mod = D("Site");
		 $da['aid']=$data['id'];
		 $field = $mod->getDbFields();
		 foreach($_POST as $k=>$v){
			 if(in_array($k,$field)){
				 $da[$k]=$v;
			 }
		 }
		 $da['addtime'] = time();

		 $mod->add($da);
		
		 

	}

	
}

?>
