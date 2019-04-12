import axios from '@/libs/api.request'

export const getTableData = (to_page, per_page, searchdata) => {
  return axios.request({
    url: '/api/admin/app_messages',
    params: {
      page: to_page,
      per_page: per_page,
      search_data: JSON.stringify(searchdata)
    },
    method: 'get'
  })
}
export const sendMessageToAppUser = (formData) => {
  return axios.request({
    url: '/api/admin/app_messages/send_message_to_app_user',
    data: formData,
    method: 'post'
  })
}
export const destroy = (id) => {
  return axios.request({
    url: '/api/admin/app_messages/' + id,
    method: 'delete'
  })
}
export const batchDestroy = (ids) => {
  return axios.request({
    url: '/api/admin/app_messages/' + ids + '/batch',
    method: 'delete'
  })
}
