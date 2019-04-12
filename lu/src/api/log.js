import axios from '@/libs/api.request'

export const getTableData = (to_page, per_page, searchdata) => {
  return axios.request({
    url: '/api/admin/logs',
    params: {
      page: to_page,
      per_page: per_page,
      search_data: JSON.stringify(searchdata)
    },
    method: 'get'
  })
}
export const getInfoById = (id) => {
  return axios.request({
    url: '/api/admin/logs/' + id,
    method: 'get'
  })
}
export const destroy = (id) => {
  return axios.request({
    url: '/api/admin/logs/' + id,
    method: 'delete'
  })
}
