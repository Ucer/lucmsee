import axios from '@/libs/api.request'

export const login = ({email, password, captcha, captcha_key}) => {
  const data = {
    email,
    password,
    captcha,
    captcha_key
  };
  return axios.request({url: 'api/login', data, method: 'post'})
}

export const getUserInfo = () => {
  return axios.request({url: 'api/admin/users/current_user', method: 'get'})
}

export const logout = (token) => {
  return axios.request({url: 'api/logout', method: 'post'})
}

export const getUnreadCount = () => {
  return axios.request({url: 'message/count', method: 'get'})
}


export const getTableData = (searchdata) => {
  return axios.request({
    url: '/api/admin/users',
    params: {
      search_data: JSON.stringify(searchdata)
    },
    method: 'get'
  })
};
