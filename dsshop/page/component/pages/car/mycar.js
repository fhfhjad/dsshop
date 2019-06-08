const getUrl = require('../../../../config').getParkingCarUrl
Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

  },

  onShow: function (options) {
    this.getParkingCarList();
  },

  //获取停车位列表
  getParkingCarList(){
    var that = this, parkingCarList={};
    wx.request({
      url: getUrl + 'getUserParkingCar',
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

  onAddParkingCar: function (e) {
    wx.navigateTo({
      url: 'add'
    })
  },

})