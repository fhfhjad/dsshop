<?php
defined('THINK_PATH') or exit();

if (is_file(__DIR__ . '/../../../aliyunc/autoload.php')) {
    require_once __DIR__ . '/../../../aliyunc/autoload.php';
}

use OSS\OssClient;
use OSS\Core\OssException;

class ParkingCarAction extends CommAction
{

    // 所有人查看到的已完成的停车位列表
    public function getParkingCar()
    {
        header("Content-Type:text/html; charset=utf-8");
        date_default_timezone_set('Asia/Shanghai');
        if (I('get.openid')) {
            $parkingCar = M('parking_car')-> order('id desc') ->where(array(
                'status' => 1
            ))->select();
            if ($parkingCar && count($parkingCar) > 0) {
                $this->ajaxReturn(count($parkingCar), $parkingCar, 1);
            } else {
                $this->ajaxReturn(0, $parkingCar, 1);
            }
        } else {
            $this->ajaxReturn(0, '非法操作', 0);
        }
    }
    
    //获取用户发布的停车记录
    public function getUserParkingCar()
    {
        header("Content-Type:text/html; charset=utf-8");
        date_default_timezone_set('Asia/Shanghai');
        if (I('get.openid')) {
            $parkingCar = M('parking_car')->order('create_time desc')->where(array(
                'user_id' => I("get.uid"),
                'status' => array('in','1,2')
            ))->select();
            if ($parkingCar && count($parkingCar) > 0) {
                $this->ajaxReturn(count($parkingCar), $parkingCar, 1);
            } else {
                $this->ajaxReturn(0, $parkingCar, 1);
            }
        } else {
            $this->ajaxReturn(0, '非法操作', 0);
        }
    }

    // 删除停车位
    public function getDeleteParkingCar()
    {
        if (I('get.openid')) {
            $user = M('user')->where(array(
                'id' => I('get.uid')
            ))->find();
            if ($user['id'] && $user['id'] > 0) {
                if (! I('get.id')) {
                    $this->ajaxReturn(0, 'ID有误', 1);
                }
                M('parking_car')->where(array(
                    'user_id' => I('get.uid'),
                    'id' => I('get.id')
                ))->delete();
                $this->ajaxReturn(1, 1, 1);
            } else {
                $this->ajaxReturn(0, 0, 1);
            }
        } else {
            $this->ajaxReturn(0, '非法操作', 0);
        }
    }

    // 停车位详情
    public function getParkingCarDetails()
    {
        if (I('get.openid')) {
            if (! I('get.id')) {
                $this->ajaxReturn(0, 'ID有误', 1);
            }
            $parkingCar = M('parking_car')->where(array(
                'user_id' => I('get.uid'),
                'id' => I('get.id')
            ))->find();
            if ($parkingCar['id'] && $parkingCar['id'] > 0) {
                $parkingCar["urls"] = M('parking_car_pic')->where(array(
                    'parking_car_id' => I('get.id')
                ))->select();
                $this->ajaxReturn(1, $parkingCar, 1);
            } else {
                $this->ajaxReturn(0, 'ID有误', 0);
            }
        } else {
            $this->ajaxReturn(0, '非法操作', 0);
        }
    }

    // 添加/修改停车位需求
    public function getAddParkingCar()
    {
        if (I('get.openid')) {
            $user = M('user')->where(array(
                'id' => I('get.uid')
            ))->find();
            if ($user['id'] && $user['id'] > 0) {
                $add['user_id'] = I('get.uid'); // 用户ID
                $add['parking_number'] = $_GET['parking_number']; // 车位号
                $add['parking_location'] = $_GET['parking_location']; // 车位位置
                $add['exchange_reason'] = $_GET['exchange_reason']; // 交换原因
                $add['village_id'] = 1; // soco公社
                $add['status'] = $_GET['status'];
                $add['create_time'] = time(); //创建时间
                if (I('get.id') > 0) { // 更新
                    $arr = split(",",I('get.uploadArr'));
                    M('parking_car')->where(array(
                        'id' => I('get.id')
                    ))->save(array(
                        'parking_number' => $add['parking_number'],
                        'parking_location' => $add['parking_location'],
                        'exchange_reason' => $add['exchange_reason'],
                        'village_id' => $add['village_id'],
                        'user_id' => $add['user_id'],
                        'status' => $add['status'],
                        'user_id' => $add['user_id'],
                        'url' => $arr[0]
                    )); 
                    //修改图片
                    $key = I('get.id');
                    $data = array();
                    foreach ($arr as $i => $value) {
                        if ($i == 0)
                            $data[$i] = array('parking_car_id'=>$key,'url' =>$value,'status' => 1);
                        else
                            $data[$i] = array('parking_car_id'=>$key,'url' =>$value,'status' => 0);
                    }

                    M('parking_car_pic')->where(array('parking_car_id'=>I('get.id')))->delete();
                    if (sizeof($data) != 0)
                        M('parking_car_pic') -> addAll($data);
                    
                } else { // 添加
                    $arr = split(",",I('get.uploadArr'));
                    $add["url"] = $arr[0];
                    M('parking_car')->add($add);
                    $key = M()->getLastInsID();
                    //保存图片
                    $data = array();
                    foreach ($arr as $i => $value) {
                        if ($i == 0)
                            $data[$i] = array('parking_car_id'=>$key,'url' =>$value,'status' => 1);
                        else 
                            $data[$i] = array('parking_car_id'=>$key,'url' =>$value,'status' => 0);
                    }
                    M('parking_car_pic') -> addAll($data);
                }
                
                $this->ajaxReturn(1, 1, 1);
            } else {
                $this->ajaxReturn(0, 0, 1);
            }
        } else {
            $this->ajaxReturn(0, '非法操作', 0);
        }
    }
    
    //test delete
//     public function deletest(){
//         M('parking_car_pic')->where(
//             array(
//                 'parking_car_id'=>I('get.id')
//             )
//         )->delete();
//         $this->ajaxReturn(1, 1, 1);
//     }

    public  function getList(){
        $list = M('parking_car_pic')->where(array(
            'parking_car_id' => I('get.id')
        ))->select();
        $this->ajaxReturn(count($list), $list, 1);
    }
    

    public function uploadImg()
    {   
        $pre = "car"; //上传文件的前缀
        $source = ''; // 上传路径
        $dest = $pre ."/"; //上传到oss的路径
        
        $info = $this->upload($pre); // Public/uploadify/uploads/car
        foreach ($info as $key => $value) {
            $source .= "" . $value['savepath'] . $value['savename']; // 我用符号把图片路径拼起来
            $dest .= $value['savename'];
        }
        $this->uploadOss($source, $dest);
    }

    public function uploadOss($source, $dest)
    {
        // 阿里云主账号AccessKey拥有所有API的访问权限，风险很高。强烈建议您创建并使用RAM账号进行API访问或日常运维，请登录 https://ram.console.aliyun.com 创建RAM账号。
        $accessKeyId = "hWPxm3BjsOXcrqaX";
        $accessKeySecret = "11rbF5nARLcOzLaDI7M8v6WtoqRzyK";
        // Endpoint以杭州为例，其它Region请按实际情况填写。
        $endpoint = "http://oss-cn-beijing.aliyuncs.com";
        // 存储空间名称
        $bucket = "dewly";
        // <yourLocalFile>由本地文件路径加文件名包括后缀组成，例如/users/local/myfile.txt
        $filePath = $source;
        // 文件名称
        $object = $dest;
        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $ossClient->uploadFile($bucket, $object, $filePath);
            $showUrl = "https://img.dewly.cn/".$object;
            $this->ajaxReturn(1, $showUrl, 1);
        } catch (OssException $e) {
            $this->ajaxReturn(0, $e->getMessage(), 0);
            return;
        }
    }
    
    // 停车位意向列表
    public function getParkingIntention()
    {
        header("Content-Type:text/html; charset=utf-8");
        date_default_timezone_set('Asia/Shanghai');
        if (I('get.openid')) {
            $parkingIntention = M('parking_intention')->where(array(
                'user_id' => I('get.uid')
            ))->select();
            if ($parkingIntention && count($parkingIntention) > 0) {
                $this->ajaxReturn(count($parkingIntention), $parkingIntention, 1);
            } else {
                $this->ajaxReturn(0, $parkingIntention, 1);
            }
        } else {
            $this->ajaxReturn(0, '非法操作', 0);
        }
    }
    
    // 删除停车位意向列表
    public function getDeleteParkingIntention()
    {
        if (I('get.openid')) {
            $user = M('user')->where(array(
                'id' => I('get.uid')
            ))->find();
            if ($user['id'] && $user['id'] > 0) {
                if (! I('get.id')) {
                    $this->ajaxReturn(0, 'ID有误', 1);
                }
                M('parking_intention')->where(array(
                    'user_id' => I('get.uid'),
                    'id' => I('get.id')
                ))->delete();
                $this->ajaxReturn(1, 1, 1);
            } else {
                $this->ajaxReturn(0, 0, 1);
            }
        } else {
            $this->ajaxReturn(0, '非法操作', 0);
        }
    }
    
    // 停车位意向详情
    public function getParkingIntentionDetails()
    {
        if (I('get.openid')) {
            if (! I('get.id')) {
                $this->ajaxReturn(0, 'ID有误', 1);
            }
            $parkingIntention = M('parking_intention')->where(array(
                'user_id' => I('get.uid'),
                'id' => I('get.id')
            ))->find();
            if ($parkingIntention['id'] && $parkingIntention['id'] > 0) {
                $this->ajaxReturn(1, $parkingIntention, 1);
            } else {
                $this->ajaxReturn(0, 'ID有误', 0);
            }
        } else {
            $this->ajaxReturn(0, '非法操作', 0);
        }
    }
    
    // 添加/修改停车位意向
    public function getAddParkingIntention()
    {
        if (I('get.openid')) {
            $user = M('user')->where(array(
                'id' => I('get.uid')
            ))->find();
            if ($user['id'] && $user['id'] > 0) {
                
                $add['parking_car_id'] = $_GET['parking_car_id']; // 车位需求ID
                $add['customer_user_id'] = $_GET['customer_user_id']; // 意向用户Id
                $add['customer_user_nick_name'] = $_GET['customer_user_nick_name']; // 意向用户昵称
                $add['comment'] = $_GET['comment']; //最后一次的聊天内容
                $add['user_id'] = I('get.uid'); // 用户ID
                $add['create_time'] = time();   //创建时间
                $add['update_time'] = time();   //更新时间
                
                if (I('get.id') > 0) { // 更新
                    M('parking_intention')->where(array(
                        'id' => I('get.id')
                    ))->save(array(
                        'parking_car_id' => $add['parking_car_id'],
                        'customer_user_id' => $add['customer_user_id'],
                        'customer_user_nick_name' => $add['customer_user_nick_name'],
                        'comment' => $add['comment'],
                        'create_time' => $add['create_time'],
                        'update_time' => $add['update_time'],
                        'user_id' => $add['user_id']
                    ));
                } else { // 添加
                    M('parking_intention')->add($add);
                }
                
                $this->ajaxReturn(1, 1, 1);
            } else {
                $this->ajaxReturn(0, 0, 1);
            }
        } else {
            $this->ajaxReturn(0, '非法操作', 0);
        }
    }
    
    //-------------------------
    // 停车位意向聊天列表
    public function getParkingIntentionMsg()
    {
        header("Content-Type:text/html; charset=utf-8");
        date_default_timezone_set('Asia/Shanghai');
        if (I('get.openid')) {
            $parkingIntentionmsg = M('parking_intention_msg')->where(array(
                'parking_intention_id' => I('get.parking_intention_id')
            ))->select();
            if ($parkingIntentionmsg && count($parkingIntentionmsg) > 0) {
                $this->ajaxReturn(count($parkingIntentionmsg), $parkingIntentionmsg, 1);
            } else {
                $this->ajaxReturn(0, $parkingIntentionmsg, 1);
            }
        } else {
            $this->ajaxReturn(0, '非法操作', 0);
        }
    }
    
    // 删除停车位意向聊天列表
    public function getDeleteParkingIntentionMsg()
    {
        if (I('get.openid')) {
            $user = M('user')->where(array(
                'id' => I('get.uid')
            ))->find();
            if ($user['id'] && $user['id'] > 0) {
                if (! I('get.id')) {
                    $this->ajaxReturn(0, 'ID有误', 1);
                }
                M('parking_intention_msg')->where(array(
                    'parking_intention_id' => I('get.parking_intention_id'),
                    'id' => I('get.id')
                ))->delete();
                $this->ajaxReturn(1, 1, 1);
            } else {
                $this->ajaxReturn(0, 0, 1);
            }
        } else {
            $this->ajaxReturn(0, '非法操作', 0);
        }
    }
    
    // 停车位意向聊天详情
    public function getParkingIntentionMsgDetails()
    {
        if (I('get.openid')) {
            if (! I('get.id')) {
                $this->ajaxReturn(0, 'ID有误', 1);
            }
            $parkingIntention = M('parking_intention')->where(array(
                'user_id' => I('get.uid'),
                'id' => I('get.id')
            ))->find();
            if ($parkingIntention['id'] && $parkingIntention['id'] > 0) {
                $this->ajaxReturn(1, $parkingIntention, 1);
            } else {
                $this->ajaxReturn(0, 'ID有误', 0);
            }
        } else {
            $this->ajaxReturn(0, '非法操作', 0);
        }
    }
    
    // 添加/修改停车位意向
    public function getAddParkingIntentionMsg()
    {
        if (I('get.openid')) {
            $user = M('user')->where(array(
                'id' => I('get.uid')
            ))->find();
            if ($user['id'] && $user['id'] > 0) {
                $add['parking_intention_id'] = $_GET['parking_intention_id']; // 车位意向ID
                $add['user_id'] = I('get.uid'); // 用户ID
                $add['type'] = $_GET['type']; // 聊天方：1发布人2客户
                $add['create_time'] = time();   //创建时间
                $add['message'] = $_GET['message']; //聊天内容
                $add['nick_name'] = $_GET['nick_name']; //聊天内容
                if (I('get.id') > 0) { // 更新
                    M('parking_intention_msg')->where(array(
                        'id' => I('get.id')
                    ))->save(array(
                        'parking_intention_id' => $add['parking_intention_id'],
                        'user_id' => $add['user_id'],
                        'type' => $add['type'],
                        'create_time' => $add['create_time'],
                        'message' => $add['message'],
                        'nick_name' => $add['nick_name']
                    ));
                } else { // 添加
                    M('parking_intention_msg')->add($add);
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