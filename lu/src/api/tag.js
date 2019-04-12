import axios from '@/libs/api.request'

export const getTableData = (searchdata) => {
  return axios.request({
    url: '/api/admin/tags',
    params: {
      search_data: JSON.stringify(searchdata)
    },
    method: 'get'
  })
}
export const add = (formData) => {
  return axios.request({ url: '/api/admin/tags', data: formData, method: 'post' })
}
export const edit = (formData, id) => {
  return axios.request({
    url: '/api/admin/tags/' + id,
    data: formData,
    method: 'patch'
  })
}
export const getInfoById = (id) => {
  return axios.request({
    url: '/api/admin/tags/' + id,
    method: 'get'
  })
}
export const destroy = (id) => {
  return axios.request({
    url: '/api/admin/tags/' + id,
    method: 'delete'
  })
}
export const getTagList = (searchdata) => {
  return axios.request({
    url: '/api/admin/tags',
    params: {
      search_data: JSON.stringify(searchdata)
    },
    method: 'get'
  })
}
export const addTag = (formData) => {
  return axios.request({ url: '/api/admin/tags', data: formData, method: 'post' })
}
