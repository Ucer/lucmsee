import axios from '@/libs/api.request'

export const getTableData = (to_page, per_page, searchdata) => {
  return axios.request({
    url: '/api/admin/databases/table_bak_records',
    params: {
      page: to_page,
      per_page: per_page,
      search_data: JSON.stringify(searchdata)
    },
    method: 'get'
  })
}

export const tableBakSqlFileDownload = (id) => {
  return axios.request({
    url: '/api/admin/databases/' + id + '/table_bak_sql_file_download',
    method: 'get',
    responseType: 'blob'
  })
}
export const destroyManyTableBakRecord = (selectes, isOpAll) => {
  return axios.request({
    url: '/api/admin/databases/destroy_many_table_bak_record',
    data: {
      is_op_all: isOpAll,
      selectes: selectes
    },
    method: 'delete'
  })
}
