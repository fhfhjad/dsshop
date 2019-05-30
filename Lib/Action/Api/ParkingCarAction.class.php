<?php
defined('THINK_PATH') or exit();

if (is_file(__DIR__ . '/../../../aliyunc/autoload.php')) {
    require_once __DIR__ . '/../../../aliyunc/autoload.php';
}

use OSS\OssClient;
use OSS\Core\OssException;

class ParkingCarAction extends CommAction
{

    // 停车位列表
    public function getParkingCar()
    {
        header("Content-Type:text/html; charset=utf-8");
        date_default_timezone_set('Asia/Shanghai');
        if (I('get.openid')) {
            $parkingCar = M('parking_car')->where(array(
                'uid' => I('get.uid')
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

    // 收货地址设为默认
    public function getDefaultAddress()
    {
        if (I('get.openid')) {
            $user = M('user')->where(array(
                'id' => I('get.uid')
            ))->find();
            if (! I('get.id')) {
                $this->ajaxReturn(0, 'ID有误', 1);
            }
            if ($user['id'] && $user['id'] > 0) {
                M('delivery')->where(array(
                    'uid' => I('get.uid')
                ))->save(array(
                    'default' => 0
                ));
                M('delivery')->where(array(
                    'uid' => I('get.uid'),
                    'id' => I('get.id')
                ))->save(array(
                    'default' => 1
                ));
                $this->ajaxReturn(1, 1, 1);
            } else {
                $this->ajaxReturn(0, 0, 1);
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

    public function uploadImg()
    {
        $source = ''; // 上传路径
        $dest = '';
        $info = $this->upload("car");
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
        // 文件名称
        $object = $dest;
        // <yourLocalFile>由本地文件路径加文件名包括后缀组成，例如/users/local/myfile.txt
        $filePath = $source;
        
        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            
            $ossClient->uploadFile($bucket, $object, $filePath);
            
            $this->ajaxReturn(1, $object, 1);
        } catch (OssException $e) {
            // printf(__FUNCTION__ . ": FAILED\n");
            // printf($e->getMessage() . "\n");
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
                'uid' => I('get.uid')
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
                
                $add['user_id'] = I('get.uid'); // 用户ID
                $add['parking_number'] = $_GET['parking_number']; // 车位号
                $add['parking_location'] = $_GET['parking_location']; // 车位位置
                $add['exchange_reason'] = $_GET['exchange_reason']; // 交换原因
                $add['village_id'] = 1; // soco公社
                $add['status'] = $_GET['status'];
                
                if (I('get.id') > 0) { // 更新
                    M('parking_intention')->where(array(
                        'id' => I('get.id')
                    ))->save(array(
                        'parking_number' => $add['parking_number'],
                        'parking_location' => $add['parking_location'],
                        'exchange_reason' => $add['exchange_reason'],
                        'village_id' => $add['village_id'],
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
   
}