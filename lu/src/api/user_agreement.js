import axios from '@/libs/api.request'

export const getTableData = (searchdata) => {
  return axios.request({
    url: '/api/admin/user_agreements',
    params: {
      search_data: JSON.stringify(searchdata)
    },
    method: 'get'
  })
}
export const add = (formData) => {
  return axios.request({ url: '/api/admin/user_agreements', data: formData, method: 'post' })
}
export const edit = (formData, id) => {
  return axios.request({
    url: '/api/admin/user_agreements/' + id,
    data: formData,
    method: 'patch'
  })
}
export const getInfoById = (id) => {
  return axios.request({
    url: '/api/admin/user_agreements/' + id,
    method: 'get'
  })
}
export const destroy = (id) => {
  return axios.request({
    url: '/api/admin/user_agreements/' + id,
    method: 'delete'
  })
}
export const enableOrDisable = (id) => {
  return axios.request({ url: '/api/admin/user_agreements/enable_or_disable/' + id, method: 'patch' })
}
