import axios from '@/libs/api.request'

export const importExcelTagUrl = window.baseUrl + '/api/excels/import_excel_tag'

export const exportExcelLog = () => {
  return axios.request({
    url: '/api/excels/export_excel_log',
    method: 'post'
  })
}
