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

                        console.log(res.data.info)
                        // that.setData({
                        //     parkingCarInfo : res.data.info
                        // });

                        wx.setNavigationBarTitle({
                            title : this.data.customer_user_nick_name
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




})