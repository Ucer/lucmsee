import axios from '@/libs/api.request'

export const login = ({
  email,
  password,
  captcha,
  captcha_key
}) => {
  const data = {
    email,
    password,
    captcha,
    captcha_key
  }
  return axios.request({
    url: 'api/login',
    data,
    method: 'post'
  })
};

export const getUserInfo = () => {
  return axios.request({
    url: 'api/admin/users/current_user',
    method: 'get'
  })
};

export const logout = (token) => {
  return axios.request({
    url: 'api/logout',
    method: 'post'
  })
};

export const getUnreadCount = () => {
  return axios.request({
    url: 'message/count',
    method: 'get'
  })
};

export const getTableData = (searchdata) => {
  return axios.request({
    url: '/api/admin/users',
    params: {
      search_data: JSON.stringify(searchdata)
    },
    method: 'get'
  })
};
export const add = (formData) => {
  return axios.request({url: '/api/admin/users', data: formData, method: 'post'})
};
export const edit = (formData, id) => {
  return axios.request({
    url: '/api/admin/users/' + id,
    data: formData,
    method: 'patch'
  })
};
export const getInfoById = (id) => {
  return axios.request({
    url: '/api/admin/users/' + id,
    method: 'get'
  })
};
export const destroy = (id) => {
  return axios.request({
    url: '/api/admin/users/' + id,
    method: 'delete'
  })
};

export const getUserRoles = (id) => {
  return axios.request({
    url: '/api/admin/users/' + id + '/roles',
    method: 'get'
  })
};

export const giveUserRole = (userId, roles) => {
  return axios.request({
    url: '/api/admin/users/give/' + userId + '/roles',
    data: {
      role: roles
    },
    method: 'post'
  })
};
