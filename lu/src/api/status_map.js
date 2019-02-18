import axios from '@/libs/api.request'

export const login = ({
  email,
  password,
  captcha,
  captcha_key
}) => {
  const data = {
    email,
    password,
    captcha,
    captcha_key
  };

export const getTableData = (searchdata) => {
  return axios.request({
    url: '/api/admin/status_maps',
    params: {
      search_data: JSON.stringify(searchdata)
    },
    method: 'get'
  })
};
export const add = (formData) => {
  return axios.request({url: '/api/admin/status_maps', data: formData, method: 'post'})
};
export const edit = (formData, id) => {
  return axios.request({
    url: '/api/admin/status_maps/' + id,
    data: formData,
    method: 'patch'
  })
};
export const getInfoById = (id) => {
  return axios.request({
    url: '/api/admin/status_maps/' + id,
    method: 'get'
  })
};
export const destroy = (id) => {
  return axios.request({
    url: '/api/admin/status_maps/' + id,
    method: 'delete'
  })
};
