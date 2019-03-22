// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.

window.$ = window.jQuery = require('jquery')

import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store'
import iView from 'iview'
import i18n from '@/locale'
import config from '@/config'
import importDirective from '@/directive'
import {directive as clickOutside} from 'v-click-outside-x'
import installPlugin from '@/plugin'
import './my-theme/default.less'
import '@/assets/icons/iconfont.css'
import TreeTable from 'tree-table-vue'
import VOrgTree from 'v-org-tree'
import 'v-org-tree/dist/v-org-tree.css'
// 实际打包时应该不引入mock
/* eslint-disable */
// if (process.env.NODE_ENV !== 'production') require('@/mock')


require('./assets/vendor/fancybox/jquery.fancybox');

const baseUrl = process.env.NODE_ENV === 'development' ? config.baseUrl.dev : config.baseUrl.pro
window.baseUrl = baseUrl
window.systemConfigIndexFile = config

window.uploadUrl = {
  imageUploadToFileSystemUrl: config.domainForFileSystem + '/api/image_upload/' + config.platName,
  fileUploadToFileSystemUrl: config.domainForFileSystem + '/api/file_upload/' + config.platName,
  uploadToLocaleUrl: baseUrl + '/api/uploads/common_upload'
}

Vue.use(iView, {
  i18n: (key, value) => i18n.t(key, value)
})
Vue.use(TreeTable)
Vue.use(VOrgTree)
/**
 * @description 注册admin内置插件
 */
installPlugin(Vue)
/**
 * @description 生产环境关掉提示
 */
Vue.config.productionTip = false
/**
 * @description 全局注册应用配置
 */
Vue.prototype.$config = config
/**
 * 注册指令
 */
importDirective(Vue)
Vue.directive('clickOutside', clickOutside)

Vue.prototype.globalPlatformType = function() {
  function IsPC() {
    var userAgentInfo = navigator.userAgent;
    var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");
    var flag = true;
    for (var v = 0; v < Agents.length; v++) {
      if (userAgentInfo.indexOf(Agents[v]) > 0) {
        flag = false;
        break;
      }
    }
    return flag;
  }

  if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
    return 'mobile' // iphone
  } else if (/(Android)/i.test(navigator.userAgent)) {
    return 'mobile' // Android
  } else {
    return 'pc'
  }
}

Vue.prototype.globalFancybox = function() {
  this.$nextTick(() => {
    $(function() {
      $('.fancybox').attr('rel', 'media-gallery').fancybox({
        openEffect: 'none',
        closeEffect: 'none',
        prevEffect: 'none',
        nextEffect: 'none',

        arrows: false,
        helpers: {
          media: {},
          buttons: {}
        }
      });
    })
  })
}

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  i18n,
  store,
  render: h => h(App)
})
