/**
  配置文件中的配置项名称不能随意改动，否则会影响到其它地方
  如： store/module/app.js中
  import config from '@/config'
   const { homeName } = config
*/
export default {
  /**
   * @description 配置显示在浏览器标签的title
   */
  title: 'Lucmsee', // 网站标题
  systemTitle: '我有一个家你不短简', // 菜单栏上方的大标题
  copyRight: 'Lucmsee', // 请务改动 copyRight，违者必纠
  /**
   * @description token在Cookie中存储的天数，默认1天 (最好与后端 oauth_token的有效时间保持一致)
   */
  cookieExpires: 1,
  /**
   * @description 是否使用国际化，默认为false
   *              如果不使用，则需要在路由中给需要在菜单中展示的路由设置meta: {title: 'xxx'}
   *              用来在菜单中显示文字
   */
  useI18n: true,
  /**
   * @description api请求基础路径
   */
  baseUrl: {
    proxy: 'http://localhost:8089', // 如果使用了代理，aioxs.js 中的 getInsideConfig baseURL修改为这个(开发环境统一使用代理)
    dev: 'http://lucmsee.test',
    pro: 'http://lucmsee.test'
  },
  /**
   * @description 默认打开的首页的路由name值，默认为home
   */
  homeName: 'home',
  /**
   * @description 需要加载的插件
   */
  plugin: {
    'error-store': {
      showInHeader: true, // 设为false后不会在顶部显示错误日志徽标
      developmentOff: true // 设为true后在开发环境不会收集错误信息，方便开发中排查错误
    }
  },
  // domainForFileSystem: 'https://filesystem.codehaoshi.com', // 文件管理系统域名，[图片文件传到文件服务器上]
  domainForFileSystem: {
    host: 'http://filesystem.test', // 文件管理系统域名，[图片文件传到文件服务器上]
    access_token: 'kjsdfiyYnsdfsadfYkT@#$'
  },
  platName: 'lucmsee', // 平台名称  plat，文件上传时用到
  noSuccessUrlArray: ['table_bak_sql_file_download'] // 如果 api url 中包含此数组中的字符串，则无需有 status 返回值，如文件上传接口

}
