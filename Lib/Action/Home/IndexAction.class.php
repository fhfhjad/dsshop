<?php

defined('THINK_PATH') or exit();
class IndexAction extends HomeAction {
    public function index(){
		//标题、关键字、描述
		$Site = D("Site");
		$site=$Site->field('keyword,remark,title,link')->where('link="'.$_SERVER['REQUEST_URI'].'"')->find();
		$this->assign('si',$site);
		$active['index']='active';
		$this->assign('active',$active);
		//推荐
		$recommend=D('Goods')->field('id,title,zimg,fid')->relation('goodslist')->limit('6')->order('`recommend` DESC')->select();
		$this->assign('recommend',$recommend);
		//人气
		$sentiment=M('goods')->field('id,title,zimg,price')->limit('6')->order('`sentiment` DESC')->select();
		$this->assign('sentiment',$sentiment);
		$shuffling = M('shuffling');
		$shufflings=$shuffling->field('title,img,url')->where('`state`=0 and type=0')->order('`order` ASC')->select();
		$shcount=$shuffling->field('title,img,url')->where('`state`=0')->count();
		$this->assign('shuff',$shufflings);
		$this->assign('shcount',$shcount);
		$this->display();
    }
}