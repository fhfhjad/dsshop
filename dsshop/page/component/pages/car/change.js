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
	    this.getParkingCarList();
	  },
	  //获取停车位列表
  getParkingCarList(){
    var that = this, parkingCarList={};
    wx.request({
      url: getUrl + 'getParkingCar',
      data: {
        openid: wx.getStorageSync('openid'),
        verify: wx.getStorageSync('verify'),
        uid: wx.getStorageSync('id'),
      },
      success: function (res) {
        if (res.data.status == 1) {
        	parkingCarList = res.data.info;
          that.setData({
        	  parkingCarList: parkingCarList,
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
	
})
