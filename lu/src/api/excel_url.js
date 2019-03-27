import axios from '@/libs/api.request'

// export const exportExcelLogUrl = window.baseUrl+'/api/excels/export_excel_log'

export const exportExcelLog = () => {
  return axios.request({
    url: '/api/excels/export_excel_log',
    method: 'post'
  })
};
