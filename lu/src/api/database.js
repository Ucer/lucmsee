import axios from '@/libs/api.request'

export const getTableData = (to_page, per_page, searchdata) => {
  return axios.request({
    url: '/api/admin/databases',
    params: {
      page: to_page,
      per_page: per_page,
      search_data: JSON.stringify(searchdata)
    },
    method: 'get'
  })
}
export const add = (formData) => {
  return axios.request({ url: '/api/admin/databases', data: formData, method: 'post' })
}
export const edit = (formData, id) => {
  return axios.request({
    url: '/api/admin/databases/' + id,
    data: formData,
    method: 'patch'
  })
}
export const getInfoById = (id) => {
  return axios.request({
    url: '/api/admin/databases/' + id,
    method: 'get'
  })
}

export const bakUpTable = (selectes, isOpAll) => {
  return axios.request({
    url: '/api/admin/databases/bak_table',
    data: {
      is_op_all: isOpAll,
      selectes: selectes
    },
    method: 'post'
  })
}
export const optimizeTable = (selectes, isOpAll) => {
  return axios.request({
    url: '/api/admin/databases/optimize_table',
    data: {
      is_op_all: isOpAll,
      selectes: selectes
    },
    method: 'post'
  })
}
export const repairTable = (selectes, isOpAll) => {
  return axios.request({
    url: '/api/admin/databases/repair_table',
    data: {
      is_op_all: isOpAll,
      selectes: selectes
    },
    method: 'post'
  })
}
