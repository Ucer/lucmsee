import axios from 'axios'
import store from '@/store'
import { Notice, Message } from 'iview'
import { getTokenFromCookies } from '@/libs/util'
import config from '@/config'

const addErrorLog = errorInfo => {
  const { statusText, status, responseData, request: {
    responseURL
  } } = errorInfo
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
  constructor (baseUrl = baseURL) {
    this.baseUrl = baseUrl
    this.queue = {}
    this.configIndex = config
  }
  getInsideConfig () {
    window.access_token = getTokenFromCookies()

    let requestBaseUrl = process.env.NODE_ENV === 'development'
      ? this.configIndex.baseUrl.proxy
      : this.baseUrl

    const config = {
      baseURL: requestBaseUrl,
      headers: {
        Authorization: window.access_token
      }
    }
    return config
  }
  destroy (url) {
    delete this.queue[url]
    if (!Object.keys(this.queue).length) {
      // Spin.hide()
    }
  }
  interceptors (instance, url) {
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
      let resData = res.data
      if (!resData.hasOwnProperty('status') || (resData.status != 'success')) {
        let response_url = res.config.url
        var noSuccessUrlArray = config.noSuccessUrlArray
        for (var i = 0; i < noSuccessUrlArray.length; i++) {
          if (response_url.indexOf(noSuccessUrlArray[i]) != -1) {
            return res.data
          }
        }

        let errorInfo = {
          status: 200,
          statusText: '接口返回非success',
          responseData: resData,
          request: {
            responseURL: response_url
          }
        }
        addErrorLog(errorInfo)

        // Notice.error({title: '接口返回非success', desc: resData, duration: 0})
        Message.error({
          content: '接口返回非success:' + resData,
          duration: 0,
          closable: true
        })

        return Promise.reject(res)
      } else {
        return res.data
      }
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
      addErrorLog(errorInfo)
      if (status === 401) { // 未登录，不记录错误日志
        Cookies.remove(TOKEN_KEY)
        window.location.href = window.location.pathname + '#/login'
      } else if (status === 412) { // 输入验证失败，不记录错误日志

      } else {}
      Notice.error({
        title: '出错了',
        desc: responseData
          ? responseData.message
          : statusText
      })
      return Promise.reject(response)
    })
  }
  request (options) {
    const instance = axios.create()
    options = Object.assign(this.getInsideConfig(), options)
    this.interceptors(instance, options.url)
    return instance(options)
  }
}
export default HttpRequest
