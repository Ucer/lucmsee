import axios from '@/libs/api.request'

export const getTableData = (searchData) => {
    return axios.request({
        url: '/api/admin/permissions',
        params: {
            search_data: JSON.stringify(searchData)
        },
        method: 'get'
    })
};
export const addEdit = (saveData) => {
    return axios.request({
        url: '/api/admin/permissions',
        data: saveData,
        method: 'post'
    })
};
export const destroy = (id) => {
    return axios.request({
        url: '/api/admin/permissions/' + id,
        method: 'delete'
    })
};

export const getInfoById = (id) => {
    return axios.request({
        url: '/api/admin/permissions/' + id,
        method: 'get'
    })
};
