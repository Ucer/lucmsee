import axios from '@/libs/api.request'

export const getTableData = (to_page, per_page, searchdata) => {
  return axios.request({
    url: '/api/admin/attachments',
    params: {
      page: to_page,
      per_page: per_page,
      search_data: JSON.stringify(searchdata)
    },
    method: 'get'
  })
};
export const add = (formData) => {
  return axios.request({url: '/api/admin/attachments', data: formData, method: 'post'})
};

export const getInfoById = (id) => {
  return axios.request({
    url: '/api/admin/attachments/' + id,
    method: 'get'
  })
};
export const destroy = (id) => {
  return axios.request({
    url: '/api/admin/attachments/' + id,
    method: 'delete'
  })
};
