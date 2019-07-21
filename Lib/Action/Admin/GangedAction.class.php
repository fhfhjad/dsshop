<?php

defined('THINK_PATH') or exit();
class GangedAction extends AdminCommAction {
//--------联动显示-----------
    public function index(){
		$unite=M('unite');
		$id=$this->_get('id')?$this->_get('id'):1;
		switch($id){
			case 1:
			$pname='开户银行';
			break;
			case 2:
			$pname='充值类型';
			break;
			case 3:
			$pname='快递公司';
			break;
			case 4:
			$pname='后台栏目分组';
			break;
		}
		$list=$unite->where('`pid`="'.$id.'"')->select();
		$this->assign('list',$list);
		$this->assign('pname',$pname);
		$endjs='
//编辑
function edit(id){
	var loading=\'<div class="invest_loading"><div><img src="__PUBLIC__/bootstrap/img/ajax-loaders/ajax-loader-1.gif"/></div><div>加载中...</div> </div>\';
	$(".integral_subject").html(loading);
		$("#edits").load("__APP__/TIFAWEB_DSWJCMS/Ganged/editajax", {id:id});
}
		';
		$this->assign('endjs',$endjs);
        $this->display();
    }
	
	//排序修改
    public function savegan(){
		$integral=D('Unite');
		$id=$this->_post("id");
		if($integral->create()){
			  $result = $integral->where(array('id'=>$id))->save();		 			
		}else{
			 $this->error($integral->getError());
		}
    }
	
	//编辑显示AJAX
    public function editajax(){
		$unite=D('Unite');
		$id=$this->_post("id");
		$list=$unite->where('`id`="'.$id.'"')->find();
		echo '
			<table class="table">
        <tbody>
          <tr>
            <td>联动名：</td>
            <td><input name="name" type="text" class="span6" placeholder="请输入联动名..." value="'.$list['name'].'"></td>
          </tr>
          <tr>
            <td>联动值：</td>
            <td><input name="value" type="text" class="span6" placeholder="请输入联动值..." value="'.$list['value'].'"></td>
          </tr>
          <tr>
            <td>状态：</td>
            <td class="form-inline">';
			if($list['state']==0){
				echo '
				<label class="radio"><input type="radio" name="state" value="0" checked/> 显示</label>
                <label class="radio"><input type="radio" name="state" value="1" /> 隐藏</label>';
			}else{
				echo '
				<label class="radio"><input type="radio" name="state" value="0" /> 显示</label>
                <label class="radio"><input type="radio" name="state" value="1" checked/> 隐藏</label>';
			}
            echo '</td>
          </tr>
		  <input name="sid" type="hidden" value="'.$id.'" />
        </tbody>      
    </table>
		';
    }
	
	//删除联动
    public function exitgan(){
		$unite=D('Unite');
		$result = $unite->where(array('id'=>$this->_get('id')))->delete();
		if($result){
			$this->Record('删除联动成功');//后台操作
			 $this->success("删除成功");
				
		}else{
			$this->Record('删除联动失败');//后台操作
			$this->error("删除失败");
		}		
	}
}
?>