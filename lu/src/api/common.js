import axios from '@/libs/api.request'

export const getCaptcha = (token) => {
  return axios.request({
    url: 'api/get_captcha',
    method: 'get'
  })
}
