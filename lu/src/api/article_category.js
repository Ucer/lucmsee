import axios from '@/libs/api.request'

export const getTableData = (searchData) => {
  return axios.request({
    url: '/api/admin/article_categories',
    params: {
      search_data: JSON.stringify(searchData)
    },
    method: 'get'
  })
}
export const add = (formData) => {
  return axios.request({ url: '/api/admin/article_categories', data: formData, method: 'post' })
}
export const edit = (formData, id) => {
  return axios.request({
    url: '/api/admin/article_categories/' + id,
    data: formData,
    method: 'patch'
  })
}
export const getInfoById = (id) => {
  return axios.request({
    url: '/api/admin/article_categories/' + id,
    method: 'get'
  })
}
export const destroy = (id) => {
  return axios.request({
    url: '/api/admin/article_categories/' + id,
    method: 'delete'
  })
}

export const getAllCategories = () => {
  return axios.request({
    url: '/api/admin/article_categories/all_article_categories',
    method: 'get'
  })
}
