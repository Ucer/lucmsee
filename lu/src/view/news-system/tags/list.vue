<template>
<div>
  <Row :gutter="24">
    <Col :xs="4" :lg="13">
    <Button type="success" icon="plus" @click="addBtn()">{{ $t('add') }}</Button>
    </Col>
    <Col :xs="4" :lg="2" class="hidden-mobile">
    <upload-file :upload-config="fileuploadConfig" @on-upload-change='uploadfileChange'></upload-file>
    </Col>
    <Col :xs="12" :lg="4" class="hidden-mobile">
    <Input icon="search" placeholder="请输入标签名称..." v-model="searchForm.name" />
    </Col>
    <Col :xs="3" :lg="3" class="hidden-mobile">
    <Button type="primary" icon="ios-search" @click="getTableDataExcute()">{{ $t('search') }}</Button>
    </Col>
  </Row>
  <br>
  <Row>
    <div class="demo-spin-container" v-if="tableLoading">
      <Spin fix>
        <Icon type="load-c" size=18 class="spin-icon-load"></Icon>
        <div>{{ $t('table_loading') }}</div>
      </Spin>
    </div>
    <Table border :columns="columns" :data="dataList" @on-sort-change='onSortChange'>
      <template slot-scope="{ row, index }" slot="action">
        <Button type="success" size="small" style="margin-right: 5px" @click="tableButtonEdit(row,index)">{{ $t('edit') }}</Button>
        <Poptip confirm :title="'您确定要删除ID为：' + row.id + ' 的记录？'" @on-ok="tableButtonDestroyOk(row,index)"> <Button type='error' size="small" style="margin-right: 5px">{{ $t('destroy')}}</Button> </Poptip>
      </template>
    </Table>
  </Row>

  <add-component v-if='addModal.show' @on-add-modal-success='getTableDataExcute' @on-add-modal-hide="addModalHide"></add-component>
  <edit-component v-if='editModal.show' :modal-id='editModal.id' @on-edit-modal-success='getTableDataExcute' @on-edit-modal-hide="editModalHide"> </edit-component>

</div>
</template>

<script>
import AddComponent from './components/add'
import EditComponent from './components/edit'
import UploadFile from '_c/common/upload-file'

import {
  importExcelTagUrl
} from '@/api/excel_url'

import {
  getTableData,
  destroy
} from '@/api/tag'

export default {
  components: {
    AddComponent,
    EditComponent,
    UploadFile
  },
  data () {
    return {
      searchForm: {
        order_by: 'id,desc'
      },
      notRealySortKey: [],
      tableLoading: true,
      dataList: [],
      addModal: {
        show: false
      },
      editModal: {
        show: false,
        id: 0
      },
      fileuploadConfig: {
        headers: {
          'Authorization': window.access_token
        },
        format: ['xlsx', 'csv', 'xls'],
        max_size: 1024 * 10, // 800KB
        upload_url: importExcelTagUrl,
        file_name: 'file',
        multiple: false,
        file_num: 1,
        data: {},
        button_text: '导入文件',
        default_list: []

      },
      columns: [{
        title: 'ID',
        key: 'id',
        sortable: 'customer',
        minWidth: 100
      },
      {
        title: '标签名称',
        key: 'name',
        minWidth: 150
      },
      {
        title: '创建时间',
        key: 'created_at',
        minWidth: 150
      },
      {
        title: '更新时间',
        key: 'created_at',
        minWidth: 150
      },
      {
        title: '操作',
        minWidth: 200,
        slot: 'action'
      }
      ]
    }
  },
  created () {
    let t = this
    t.getTableDataExcute()
  },
  methods: {
    getTableDataExcute () {
      let t = this
      t.tableLoading = true
      getTableData(t.searchForm).then(res => {
        const response_data = res.data
        t.dataList = response_data
        t.tableLoading = false
      }, function (error) {
        t.tableLoading = false
      })
    },
    tableButtonEdit (row, index) {
      this.editModal.show = true
      this.editModal.id = row.id
    },
    tableButtonDestroyOk (row, index) {
      let t = this
      destroy(row.id).then(res => {
        t.dataList.splice(index, 1)
        t.$Notice.success({
          title: res.message
        })
      })
    },
    onSortChange: function (data) {
      const order = data.column.key + ',' + data.order
      if (oneOf(data.column.key, this.notRealySortKey)) {

      } else {
        this.searchForm.order_by = order
        this.getTableDataExcute(this.feeds.current_page)
      }
    },
    addBtn () {
      this.addModal.show = true
    },
    addModalHide () {
      this.addModal.show = false
    },
    editModalHide () {
      this.editModal.show = false
    },
    uploadfileChange (fileList, formatFileList) {}
  }
}
</script>
