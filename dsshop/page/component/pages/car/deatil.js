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
      // title = '车位详情';
      //获取收货地址
      wx.request({
        url : getUrl + 'getParkingCarDetails',
        data : {
          id : options.id,
          openid : wx.getStorageSync('openid'),
          verify : wx.getStorageSync('verify'),
          uid : wx.getStorageSync('id'),
        },
        success : function(res) {
          if (res.data.status == 1) {
            var arr = res.data.info.urls;
            var arrUlr = [];
            for (var i = 0; i < arr.length; i++) {
              arrUlr[i] = arr[i].url;
            }
            that.setData({
              parkingCarInfo : res.data.info,
              imagesUrl:arrUlr
            });
          } else {
            wx.showToast({
              title : res.data.info,
              icon : 'none',
            })
          }
        }
      })
    } else {
      title = '发布交换车位';
    }

    // wx.setNavigationBarTitle({
    //   title : title
    // })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },




})