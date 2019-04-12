import axios from '@/libs/api.request'

export const getTableData = (to_page, per_page, searchdata) => {
  return axios.request({
    url: '/api/admin/admin_messages',
    params: {
      page: to_page,
      per_page: per_page,
      search_data: JSON.stringify(searchdata)
    },
    method: 'get'
  })
}
export const sendMessageToAdmin = (formData) => {
  return axios.request({
    url: '/api/admin/admin_messages/send_message_to_admin',
    data: formData,
    method: 'post'
  })
}
export const destroy = (id) => {
  return axios.request({
    url: '/api/admin/admin_messages/' + id,
    method: 'delete'
  })
}
export const readOne = (id) => {
  return axios.request({
    url: '/api/admin/admin_messages/read/' + id,
    method: 'patch'
  })
}
export const readAll = () => {
  return axios.request({
    url: '/api/admin/admin_messages/read_all',
    method: 'patch'
  })
}
export const batchDestroy = (ids) => {
  return axios.request({
    url: '/api/admin/admin_messages/' + ids + '/batch',
    method: 'delete'
  })
}
