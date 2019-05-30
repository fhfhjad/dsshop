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
			title = '编辑交换车位';
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
						that.setData({
							parkingCarInfo : res.data.info,
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
		
		 wx.showLoading({
		        title: '正在创建...',
		        mask: true
		 })

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
				status:status,
				uploadArr:this.data.imagesUrl.toString()
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
		
	//选择图片
	  chooseImage(e) {
		
		var that = this;
		
	    wx.chooseImage({
	      sizeType: ['original', 'compressed'],  //可选择原图或压缩后的图片
	      sourceType: ['album', 'camera'], //可选择性开放访问相册、相机
	      success: res => {
	        const images = this.data.images.concat(res.tempFilePaths)
//	        console.log(images);
	        // 限制最多只能留下3张照片
	        this.data.images = images.length <= 3 ? images : images.slice(0, 3) 
	        $digest(this)
	        
	        //上传图片
	        this.data.images.map(path =>{
				wx.uploadFile({
					// 省略
					url:getUrl + 'uploadImg',
					filePath: path,
					name: 'uploadFile',
					success(res) {
						var data = JSON.parse(res.data);
						that.data.imagesUrl.push(data.info);
					},
					fail() {
						console.log("上传失败")
					}
				})
	       })
	        
	      }
	    })
	  },
	  
	  //删除图片
	  removeImage(e) {
		    const idx = e.target.dataset.idx
		    this.data.images.splice(idx, 1)
		    $digest(this)
		  },
	  //预览图片
	  handleImagePreview(e) {
	    const idx = e.target.dataset.idx
	    const images = this.data.images
	    wx.previewImage({
	      current: images[idx],  //当前预览的图片
	      urls: images,  //所有要预览的图片
	    })
	  }
	
})