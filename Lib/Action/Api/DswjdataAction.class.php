<?php

defined('THINK_PATH') or exit();
class DswjdataAction extends CommAction {
	/**
	 * @Dswjcms数据接口
	 * @作者		shop猫
	 * @版权		都丽社区
	 * @官网		http://www.tifaweb.com http://www.dswjcms.com
	 */
	public function index(){
		$Borrow=D('Borrowing');
		$borrow=$Borrow->field('id,type,title,rates,deadline,candra,way,valid,min,money,content,reward,reward_type')->where('(`type`<3 or `type`=8)')->select();
		foreach($borrow as $id=>$b){
			if($b['type']==8){
				
				$borrow[$id]['type']=3;
				
			}
			$borrow[$id]['bid']=$b['id'];
			$borrow[$id]['url']='http://'.$_SERVER['HTTP_HOST'].'/Loan/invest/'.$borrow[$id]['id'].'.html';
			unset($borrow[$id]['id']);
			$borrow[$id]['platform']=1;	//提供的平台唯一KEY
		}
		$borrow=array_filter($borrow);
		if(count($borrow)>0){	//如果有获取数
			$borrow['state']='yes';
		}else{
			$borrow['state']='no';
		}
		echo json_encode($borrow);
	}
}