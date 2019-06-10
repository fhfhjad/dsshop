const uploadImgUrl = require('../../../../config').uploadImgUrl
const getUrl = require('../../../../config').getParkingCarUrl
import { $init, $digest } from '../../../../utils/common.util'

Page({

    /**
     * 页面的初始数据
     */
    data: {
        images: [], //临时目录
        imagesUrl: []
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        $init(this);
        var title, that = this;
        if (options.id) {
            this.setData({
                id : options.id
            })

            //获取收货地址
            wx.request({
                url : getUrl + 'getParkingIntentionDetails',
                data : {
                    id : options.id,
                    openid : wx.getStorageSync('openid'),
                    verify : wx.getStorageSync('verify'),
                    uid : wx.getStorageSync('id'),
                },
                success : function(res) {
                    if (res.data.status == 1) {

                        //设置名字为标题
                        wx.setNavigationBarTitle({
                          title: res.data.info.customer_user_nick_name
                        })

                        //获取车位详情
                        wx.request({
                            url : getUrl + 'getParkingCarDetails',
                            data : {
                                id : res.data.info.parking_car_id,
                                openid : wx.getStorageSync('openid'),
                                verify : wx.getStorageSync('verify'),
                                uid : wx.getStorageSync('id'),
                            },
                            success : function(res) {
                                if (res.data.status == 1) {
                                    that.setData({
                                        parkingCarInfo : res.data.info
                                    });
                                } else {
                                    wx.showToast({
                                        title : res.data.info,
                                        icon : 'none',
                                    })
                                }
                            }
                        })

                        //获取聊天列表
                        wx.request({
                            url: getUrl + 'getParkingIntentionMsg',
                            data: {
                                openid: wx.getStorageSync('openid'),
                                verify: wx.getStorageSync('verify'),
                                uid: wx.getStorageSync('id'),
                                parking_intention_id: options.id
                            },
                            success: function (res) {

                                var parkingIntentionMsgList = res.data.info;

                                if (res.data.status == 1) {
                                    that.setData({
                                        parkingIntentionMsgList: parkingIntentionMsgList,
                                    });
                                } else {
                                    wx.showToast({
                                        title: res.data.info,
                                        icon: 'none',
                                    })
                                }
                            }
                        })


                    } else {
                        wx.showToast({
                            title : res.data.info,
                            icon : 'none',
                        })
                    }
                }
            })
        } else {
            wx.showToast({
                title : '消息不存在',
                icon : 'none',
            })
        }


    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {

    },

    bindChange: function(e) {
        this.setData({
            message : e.detail.value
        });
    },
    //事件处理函数
    add: function(e) {
        var that = this;
        //发送聊天
        wx.request({
            url : getUrl + 'getAddParkingIntentionMsg',
            data : {
                openid : wx.getStorageSync('openid'),
                verify : wx.getStorageSync('verify'),
                uid : wx.getStorageSync('id'),
                message:this.data.message,
                parking_intention_id: this.data.id
            },
            success : function(res) {
                if (res.data.status == 1) {

                  //获取聊天列表
                  wx.request({
                    url: getUrl + 'getParkingIntentionMsg',
                    data: {
                      openid: wx.getStorageSync('openid'),
                      verify: wx.getStorageSync('verify'),
                      uid: wx.getStorageSync('id'),
                      parking_intention_id: that.data.id
                    },
                    success: function (res) {

                      var parkingIntentionMsgList = res.data.info;

                      if (res.data.status == 1) {
                        that.setData({
                          parkingIntentionMsgList: parkingIntentionMsgList,
                        });
                          $digest(that)
                      } else {
                        wx.showToast({
                          title: res.data.info,
                          icon: 'none',
                        })
                      }
                    }
                  })

                } else {
                    wx.showToast({
                        title : res.data.info,
                        icon : 'none',
                    })
                }
            }
        })


    },


})