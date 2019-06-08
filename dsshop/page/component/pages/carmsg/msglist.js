const getUrl = require('../../../../config').getParkingCarUrl
Page({

    /**
     * 页面的初始数据
     */
    data : {

    },
    onLoad : function(options) {

    },

    onShow: function (options) {
        this.getParkingIntention();
    },
    //获取停车位列表
    getParkingIntention(){
        var that = this, parkingIntentionList={};
        wx.request({
            url: getUrl + 'getParkingIntention',
            data: {
                openid: wx.getStorageSync('openid'),
                verify: wx.getStorageSync('verify'),
                uid: wx.getStorageSync('id'),
            },
            success: function (res) {
                if (res.data.status == 1) {
                    parkingIntentionList = res.data.info;
                    that.setData({
                        parkingIntentionList: parkingIntentionList,
                    });
                } else {
                    wx.showToast({
                        title: res.data.info,
                        icon: 'none',
                    })
                }
            }
        })
    },

    onAddParkingCar: function (e) {
        wx.navigateTo({
            url: 'add'
        })
    },

})
