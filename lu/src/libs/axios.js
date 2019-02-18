import axios from 'axios'
import store from '@/store'
import {Notice} from 'iview'
import {getTokenFromCookies} from '@/libs/util'

const addErrorLog = errorInfo => {
  const {statusText, status, responseData, request: {
      responseURL
    }} = errorInfo
  let info = {
    type: 'ajax',
    code: status, // http 状态码
    message: statusText, // http 错误信息
    response_data: responseData, // http 响应头信息
    url: responseURL
  }
  console.log(info)
  // if (!responseURL.includes('save_error_logger')) store.dispatch('addErrorLog', info)
}

class HttpRequest {
  constructor(baseUrl = baseURL) {
    this.baseUrl = baseUrl
    this.queue = {}
  }
  getInsideConfig() {
    window.access_token = getTokenFromCookies()
    let requestBaseUrl = process.env.NODE_ENV === 'development'
      ? config.baseUrl.proxy
      : config.baseUrl.pro

    const config = {
      baseURL: requestBaseUrl,
      headers: {
        Authorization: window.access_token
      }
    }
    return config
  }
  destroy(url) {
    delete this.queue[url]
    if (!Object.keys(this.queue).length) {
      // Spin.hide()
    }
  }
  interceptors(instance, url) {
    // 请求拦截
    instance.interceptors.request.use(config => {
      // this.queue[url] = true
      return config
    }, error => {
      return Promise.reject(error)
    })
    // 响应拦截
    instance.interceptors.response.use(res => {
      this.destroy(url)
      return res.data
    }, error => {
      this.destroy(url)
      const {
        response: {
          statusText,
          status
        },
        config,
        response
      } = JSON.parse(JSON.stringify(error))
      const responseData = response.data // 包含具体地错误信息

      let errorInfo = {
        statusText,
        status,
        responseData,
        request: {
          responseURL: config.url
        }
      }
      if (status === 401) { // 未登录，不记录错误日志
        Cookies.remove(TOKEN_KEY)
        window.location.href = window.location.pathname + '#/login'
      } else if (status === 412) { // 输入验证失败，不记录错误日志

      } else {
        addErrorLog(errorInfo)
      }
      Notice.error({
        title: '出错了',
        desc: responseData
          ? responseData.message
          : statusText
      })
      return Promise.reject(response)
    })
  }
  request(options) {
    const instance = axios.create()
    options = Object.assign(this.getInsideConfig(), options)
    this.interceptors(instance, options.url)
    return instance(options)
  }
}
export default HttpRequest
