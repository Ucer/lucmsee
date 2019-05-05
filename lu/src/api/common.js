import axios from '@/libs/api.request'

export const getCaptcha = (token) => {
  return axios.request({ url: 'api/get_captcha', method: 'get' })
}
export const getTableStatus = (param) => {
  return axios.request({
    url: '/api/common_get_table_status/' + param,
    method: 'get'
  })
}
export const getConfigFileData = (param) => {
  return axios.request({
    url: '/api/common_get_config_file_data',
    data: { config_item: param },
    method: 'post'
  })
}

export const switchEnable = (id, table, value) => {
  return axios.request({
    url: '/api/common_switch_enable',
    data: {
      id: id,
      table: table,
      value: value
    },
    method: 'post'
  })
}

export const commonEditTableColumn = (id, table, column, value) => {
  return axios.request({
    url: '/api/common_edit_talbe_column',
    data: {
      id: id,
      table: table,
      column: column,
      value: value
    },
    method: 'post'
  })
}
