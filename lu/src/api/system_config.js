import axios from '@/libs/api.request'

export const getTableData = (to_page, per_page, searchdata) => {
  return axios.request({
    url: '/api/admin/system_configs',
    params: {
      page: to_page,
      per_page: per_page,
      search_data: JSON.stringify(searchdata)
    },
    method: 'get'
  })
}
export const add = (formData) => {
  return axios.request({ url: '/api/admin/system_configs', data: formData, method: 'post' })
}
export const edit = (formData, id) => {
  return axios.request({
    url: '/api/admin/system_configs/' + id,
    data: formData,
    method: 'patch'
  })
}
export const getInfoById = (id) => {
  return axios.request({
    url: '/api/admin/system_configs/' + id,
    method: 'get'
  })
}
export const destroy = (id) => {
  return axios.request({
    url: '/api/admin/system_configs/' + id,
    method: 'delete'
  })
}

export const getGroup = () => {
  return axios.request({ url: '/api/admin/system_configs/get_system_config_group', method: 'get' })
}
