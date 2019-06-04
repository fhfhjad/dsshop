<?php

defined('THINK_PATH') or exit();
class InstationAction extends AdminCommAction {
//--------站内信---------
	//站内信发送页
	public function index(){
		$User=M('user');
		$this->display();
	}
	//站内信发送
	public function sends(){
		$type=$this->_post('type');	
		$arr['title']=$this->_post('title');
		$arr['sid']=$this->_post('sid');
		$arr['msg']=$this->_post('msg');
		if($arr['title']){
			if($type==1){//群发
				$arr['type']=1;
				$this->silMass($arr);
			}else{
				$this->silSingle($arr);
			}
			$this->success("发送成功","__APP__/TIFAWEB_DSWJCMS/Instation.html");
		}else{
			$this->error("信息必须完整");	
		}
		
	}	
}
?>