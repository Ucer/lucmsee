import axios from '@/libs/api.request'

export const getTableData = (searchdata) => {
  return axios.request({
    url: '/api/admin/app_versions',
    params: {
      search_data: JSON.stringify(searchdata)
    },
    method: 'get'
  })
}
export const add = (formData) => {
  return axios.request({ url: '/api/admin/app_versions', data: formData, method: 'post' })
}
export const edit = (formData, id) => {
  return axios.request({
    url: '/api/admin/app_versions/' + id,
    data: formData,
    method: 'patch'
  })
}
export const getInfoById = (id) => {
  return axios.request({
    url: '/api/admin/app_versions/' + id,
    method: 'get'
  })
}
