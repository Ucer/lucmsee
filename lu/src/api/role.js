import axios from '@/libs/api.request'

export const getTableData = (searchdata) => {
  return axios.request({
    url: '/api/admin/roles',
    params: {
      search_data: JSON.stringify(searchdata)
    },
    method: 'get'
  })
}
export const add = (formData) => {
  return axios.request({ url: '/api/admin/roles', data: formData, method: 'post' })
}
export const edit = (formData, id) => {
  return axios.request({
    url: '/api/admin/roles/' + id,
    data: formData,
    method: 'patch'
  })
}
export const getInfoById = (id) => {
  return axios.request({
    url: '/api/admin/roles/' + id,
    method: 'get'
  })
}
export const destroy = (id) => {
  return axios.request({
    url: '/api/admin/roles/' + id,
    method: 'delete'
  })
}

export const getAllPermission = () => {
  return axios.request({ url: '/api/admin/all_permissions', method: 'get' })
}
export const getRolePermissions = (roleId) => {
  return axios.request({
    url: 'api/admin/roles/' + roleId + '/permissions',
    method: 'get'
  })
}
export const giveRolePermission = (roleId, permissions) => {
  return axios.request({
    url: '/api/admin/give/' + roleId + '/permissions',
    data: {
      permission: permissions
    },
    method: 'post'
  })
}

export const getAllRole = () => {
  return axios.request({ url: '/api/admin/roles/all_roles', method: 'get' })
}
