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

      //获取车位详情
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
        wx.showToast({
            title : '车位不存在',
            icon : 'none',
        })
    }


  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

    },

    //提交订单
    getGenerateOrders() {

        wx.request({
            url : getUrl + 'getAddParkingIntention',
            data : {
                openid : wx.getStorageSync('openid'),
                verify : wx.getStorageSync('verify'),
                uid : wx.getStorageSync('id'),
                parking_car_id : this.data.id
            },
            success : function(res) {
                console.log(res);
                if (res.data.status == 1) {
                    //wx.navigateBack();

                } else {
                    wx.showToast({
                        title : res.data.info,
                        icon : 'none',
                    })
                }
            }
        })

    }


})