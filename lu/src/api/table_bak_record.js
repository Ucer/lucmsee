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
};
