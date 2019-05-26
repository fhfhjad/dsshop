const getUrl = require('../../../../config').getUrl
Page({
  onLoad: function (options) {
    var that=this;
    console.log(options);
    console.log(getUrl + 'content?id=' + options.id);
    that.setData({
      src: getUrl + 'content?id=' + options.id ,
      // src: getUrl + 'content?id=' + options.id + '&gid=' + options.gid,
    });
  },
})