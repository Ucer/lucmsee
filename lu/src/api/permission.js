import axios from '@/libs/api.request'

export const getTableData = (searchData) => {
  return axios.request({
    url: '/api/admin/permissions',
    params: {
      search_data: JSON.stringify(searchData)
    },
    method: 'get'
  })
}
export const add = (formData) => {
  return axios.request({ url: '/api/admin/permissions', data: formData, method: 'post' })
}
export const edit = (formData, id) => {
  return axios.request({
    url: '/api/admin/permissions/' + id,
    data: formData,
    method: 'patch'
  })
}
export const getInfoById = (id) => {
  return axios.request({
    url: '/api/admin/permissions/' + id,
    method: 'get'
  })
}
export const destroy = (id) => {
  return axios.request({
    url: '/api/admin/permissions/' + id,
    method: 'delete'
  })
}
