require('./base')
require('../amazeui/assets/js/amazeui.min.js')

require('./vendor/fancybox/jquery.fancybox');

// window.Swiper = require('./vendor/swiperjs/swiper');
window.Vue = require('vue');

import axios from 'axios'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
/*
import Highlight from './vendor/highlight/highlight'
Vue.use(Highlight)
*/



Object.defineProperty(Vue.prototype, '$axios', {value: axios}); // 全局能使用 this.$axios

Vue.use(ElementUI)

Vue.component('globalMessage', require('./components/GlobalMessage.vue').default)
Vue.component('parseArticleInfo', require('./components/ParseArticleInfo.vue').default)


var vm = new Vue({
    el: '#app'
})