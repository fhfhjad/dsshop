<?php
defined('THINK_PATH') or exit();
class ParkingCarAction extends CommAction {
    

    //收货地址默认展示
    public function getShippingAddressShow(){
        header("Content-Type:text/html; charset=utf-8");
        date_default_timezone_set('Asia/Shanghai');
        if(I('get.openid')){
            $delivery=M('delivery')->where(array('uid'=>I('get.uid'),'default'=>1))->find();
            if($delivery && count($delivery)>0){
                $city=M('newcity')->select();
                foreach($city as $cy){
                    $citys[$cy['id']]=$cy['city'];
                }
                $information=json_decode($delivery['information'], true);
                $delivery['information']=$information;
                $delivery['city']=$citys[$information['region'][0]].' '.$citys[$information['region'][1]].' '.$citys[$information['region'][2]];
                $this->ajaxReturn(1,$delivery,1);
            }else{
                $this->ajaxReturn(0,'',0);
            }

        }else{
            $this->ajaxReturn(0,'非法操作',0);
        }
    }

    //收货地址
    public function getShippingAddress(){
        header("Content-Type:text/html; charset=utf-8");
        date_default_timezone_set('Asia/Shanghai');
        if(I('get.openid')){
            $delivery=M('delivery')->where(array('uid'=>I('get.uid')))->select();
            if($delivery && count($delivery)>0){
                $city=M('newcity')->select();
                foreach($city as $cy){
                    $citys[$cy['id']]=$cy['city'];
                }
                foreach($delivery as $id=>$d){
                    $information=json_decode($d['information'], true);
                    $delivery[$id]['information']=$information;
                    $delivery[$id]['city']=$citys[$information['region'][0]].' '.$citys[$information['region'][1]].' '.$citys[$information['region'][2]];
                    unset($information);
                }
                $this->ajaxReturn(count($delivery),$delivery,1);
            }else{
                $this->ajaxReturn(0,$delivery,1);
            }

        }else{
            $this->ajaxReturn(0,'非法操作',0);
        }
    }

    //收货地址设为默认
    public function getDefaultAddress(){
        if(I('get.openid')){
            $user=M('user')->where(array('id'=>I('get.uid')))->find();
            if(!I('get.id')){
                $this->ajaxReturn(0,'ID有误',1);
            }
            if($user['id'] && $user['id']>0){
                M('delivery')->where(array('uid'=>I('get.uid')))->save(array('default'=>0));
                M('delivery')->where(array('uid'=>I('get.uid'),'id'=>I('get.id')))->save(array('default'=>1));
                $this->ajaxReturn(1,1,1);
            }else{
                $this->ajaxReturn(0,0,1);
            }

        }else{
            $this->ajaxReturn(0,'非法操作',0);
        }
    }

    //删除收货地址
    public function getDeleteAddress(){
        if(I('get.openid')){
            $user=M('user')->where(array('id'=>I('get.uid')))->find();
            if($user['id'] && $user['id']>0){
                if(!I('get.id')){
                    $this->ajaxReturn(0,'ID有误',1);
                }
                M('delivery')->where(array('uid'=>I('get.uid'),'id'=>I('get.id')))->delete();
                $this->ajaxReturn(1,1,1);
            }else{
                $this->ajaxReturn(0,0,1);
            }

        }else{
            $this->ajaxReturn(0,'非法操作',0);
        }
    }

    //收货地址详情
    public function getAddressDetails(){
        if(I('get.openid')){
            if(!I('get.id')){
                $this->ajaxReturn(0,'ID有误',1);
            }
            $delivery=M('delivery')->where(array('uid'=>I('get.uid'),'id'=>I('get.id')))->find();
            if($delivery['id'] && $delivery['id']>0){
                $delivery['information']=json_decode($delivery['information'], true);
                $city=M('newcity')->select();
                foreach($city as $cy){
                    $citys[$cy['id']]=$cy['city'];
                }
                $delivery['cityarray']=array($citys[$delivery['information']['region'][0]],$citys[$delivery['information']['region'][1]],$citys[$delivery['information']['region'][2]]);
                $delivery['information']['city']=$citys[$delivery['information']['region'][0]].','.$citys[$delivery['information']['region'][1]].','.$citys[$delivery['information']['region'][2]];
                $this->ajaxReturn(1,$delivery,1);
            }else{
                $this->ajaxReturn(0,'ID有误',0);
            }

        }else{
            $this->ajaxReturn(0,'非法操作',0);
        }
    }

    //添加/修改停车位需求
    public function getAddParkingCar()
    {
        if (I('get.openid')) {
            $user = M('user')->where(array(
                'id' => I('get.uid')
            ))->find();
            if ($user['id'] && $user['id'] > 0) {
                
                $add['user_id'] = I('get.uid'); //用户ID
                $add['parking_number'] = $_GET['parking_number']; //车位号
                $add['parking_location'] = $_GET['parking_location']; //车位位置
                $add['exchange_reason'] = $_GET['exchange_reason']; //交换原因
                $add['village_id'] = 1; //soco公社
                $add['status'] = $_GET['status'];
                
                if (I('get.id') > 0) { // 更新
                    M('parking_car')->where(array(
                        'id' => I('get.id')
                    ))->save(array(
                        'parking_number' => $add['parking_number'],
                        'parking_location' => $add['parking_location'],
                        'exchange_reason' => $add['exchange_reason'],
                        'village_id' => $add['village_id'],
                        'user_id' => $add['user_id']
                    ));
                } else { // 添加
                    M('parking_car')->add($add);
                }
                
                $this->ajaxReturn(1, 1, 1);
            } else {
                $this->ajaxReturn(0, 0, 1);
            }
        } else {
            $this->ajaxReturn(0, '非法操作', 0);
        }
    }

   
}