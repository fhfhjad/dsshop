const uploadImgUrl = require('../../../../config').uploadImgUrl
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
	  var title, that = this;
		
		if (options.id) {
			this.setData({
				id : options.id
			})
			title = '编辑交换车位';
			//获取收货地址
			wx.request({
				url : getUrl + 'getAddressDetails',
				data : {
					id : options.id,
					openid : wx.getStorageSync('openid'),
					verify : wx.getStorageSync('verify'),
					uid : wx.getStorageSync('id'),
				},
				success : function(res) {

					if (res.data.status == 1) {
						that.setData({
							Address : res.data.info,
							region : res.data.info.information.city,
							cityarray : res.data.info.cityarray
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

		wx.setNavigationBarTitle({
			title : title
		})
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  formSubmit : function(e) {

		var that = this, 
		val = e.detail.value, 
		id = this.data.id ? this.data.id : 0;
		
		var parking_number = val.parking_number;
		var parking_location = val.parking_location;
		var exchange_reason = val.exchange_reason;
		var status = val.status?val.status:1;
		
		if (!val.parking_number) {
			wx.showToast({
				title : '车位号不能为空',
				icon : 'none',
			})
			return false;
		}

		wx.request({
			url : getUrl + 'getAddParkingCar',
			data : {
				openid : wx.getStorageSync('openid'),
				verify : wx.getStorageSync('verify'),
				uid : wx.getStorageSync('id'),
				id : id,
				parking_number:parking_number,
				parking_location:parking_location,
				exchange_reason:exchange_reason,
				status:status
			},
			success : function(res) {
				//console.log(res);
				if (res.data.status == 1) {
					wx.navigateBack();
				} else {
					wx.showToast({
						title : res.data.info,
						icon : 'none',
					})
				}
			}
		})

		//console.log(val);

	},
})