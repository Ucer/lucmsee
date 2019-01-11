import axios from '@/libs/api.request'

export const login = ({userName, password}) => {
  const data = {
    userName,
    password
  }
  return axios.request({url: 'login', data, method: 'post'})
}

export const getUserInfo = () => {
  return axios.request({url: 'api/admin/users/current_user', method: 'get'})
}

export const logout = (token) => {
  return axios.request({url: 'logout', method: 'post'})
}

export const getUnreadCount = () => {
  return axios.request({url: 'message/count', method: 'get'})
}
