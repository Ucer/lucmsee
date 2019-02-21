<template>
<div>
  <Row :gutter="24">
    <Col :xs="7" :lg="2">
    <Button type="success" icon="plus" @click="addBtn()">{{ $t('add') }}</Button>
    </Col>
    <Col :xs="4" :lg="2" class="hidden-mobile">
    <Poptip confirm placement="bottom" title="确认要操作?" @on-ok="bakUpTableExcute(selectIds,false)" ok-text="确认" cancel-text="点错了">
      <Button>备份</Button>
    </Poptip>
    </Col>
    <Col :xs="6" :lg="3" class="hidden-mobile">
    <Input icon="search" placeholder="请输入表名搜索..." v-model="searchForm.table_name"></Input>
    </Col>
    <Col :xs="3" :lg="3">
    <Button type="primary" icon="ios-search" @click="getTableDataExcute(feeds.current_page)">{{ $t('search') }}</Button>
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
    <Table height="600" size='small' :columns="columns" :data="feeds.data" @on-sort-change='onSortChange' @on-selection-change='onSelectionChange'>
      <template slot-scope="{ row, index }" slot="action">
        <Button type="primary" size="small" style="margin-right: 5px" @click="tableButtonShowInfo(row,index)">{{ $t('show_info') }}</Button>
        <Button type="success" size="small" style="margin-right: 5px" @click="tableButtonEdit(row,index)">{{ $t('edit') }}</Button>
        <Poptip confirm :title="'您确定要删除ID为：' + row.id + ' 的记录？'" @on-ok="tableButtonDestroyOk(row,index)"> <Button type='error' size="small" style="margin-right: 5px">{{ $t('destroy')}}</Button> </Poptip>
      </template>
    </Table>
    <div style="margin: 10px;overflow: hidden">
      <div style="float: right;">
        {{ all_tables_num }} 张表，{{ all_tables_length}}
      </div>
    </div>
  </Row>

  <!-- <show-info v-if='showInfoModal.show' :info='showInfoModal.info' @show-modal-close="showModalClose"></show-info> -->
  
</div>
</template>

<script>
import AddComponent from './components/add'
import EditComponent from './components/edit'


import {
  getTableData,
  bakUpTable
} from '@/api/database'

export default {
  components: {
    AddComponent,
    EditComponent
  },
  data() {
    return {
      searchForm: {
        order_by: 'created_at,desc',
        table_name: '',
        column: ''
      },
      tableLoading: false,
      feeds: {
        data: [],
        total: 0,
        current_page: 1,
        per_page: 10
      },
      addModal: {
        show: false
      },
      editModal: {
        show: false,
        id: 0
      },
      showInfoModal: {
        show: false,
        info: ''
      },
      all_tables_num: 0,
      all_tables_length: 0,
      selectIds: '',
      columns: [{
          type: 'selection',
          width: 60,
          align: 'center'
        },
        {
          title: '表名',
          key: 'Name',
          minWidth: 100,
        },
        {
          title: '存储引擎',
          key: 'Engine',
          minWidth: 60,
        }, {
          title: '行数',
          key: 'Rows',
          minWidth: 60,
          sortable: true
        }, {
          title: '数据',
          key: 'Data_length',
          minWidth: 60,
          sortable: true
        }, {
          title: '索引',
          key: 'Index_length',
          minWidth: 60,
          sortable: true
        }, {
          title: '全部',
          key: 'Total_length',
          minWidth: 60,
          sortable: true
        },
        {
          title: '创建时间',
          key: 'Create_time',
          sortable: true,
          minWidth: 150,
        }, {
          title: '修改时间',
          key: 'Ureate_time',
          sortable: true,
          minWidth: 150,
        }, {
          title: '操作',
          key: '',
          minWidth: 200,
          slot: 'action'
        },
      ],

    }
  },
  created() {
    let t = this
    t.getTableDataExcute(t.feeds.current_page)
  },
  methods: {
    getTableDataExcute(to_page) {
      let t = this
      t.tableLoading = true
      t.feeds.current_page = to_page
      getTableData(to_page, t.feeds.per_page, t.searchForm).then(res => {
        t.feeds.data = res.data.data
        t.all_tables_num = res.data.all_tables_num
        t.all_tables_length = res.data.all_tables_length
        t.feeds.total = 0
        t.tableLoading = false
      }, function(error) {
        t.tableLoading = false
      })

    },
    onSortChange: function(data) {
      const order = data.column.key + ',' + data.order
      this.searchForm.order_by = order
      // this.getTableDataExcute(this.feeds.current_page)
    },
    tableButtonEdit(row, index) {
      this.editModal.show = true
      this.editModal.id = row.id
    },
    tableButtonDestroyOk(row, index) {
      let t = this
      destroy(row.id).then(res => {
        t.feeds.data.splice(index, 1)
        t.$Notice.success({
          title: res.message
        })
      })
    },
    tableButtonShowInfo(row, index) {
      this.showInfoModal.show = true
      this.showInfoModal.info = row
    },
    onSelectionChange: function(selection) {
      this.selectIds = ''
      for (let index in selection) {
        this.selectIds += ',' + selection[index].Name
      }
    },
    addBtn() {
      this.addModal.show = true
    },
    addModalHide() {
      this.addModal.show = false
    },
    editModalHide() {
      this.editModal.show = false
    },
    cancelRoleModal() {
      let t = this
      t.roleModal.show = false
      t.roleModal.saveLoading = false
    },
    showModalClose() {
      this.showInfoModal.show = false
    },
    bakUpTableExcute(selectes, isOpAll) {
      if (isOpAll === false && !selectes) {
        this.$Notice.error({
          title: '出错了',
          desc: '请先选择要操作的项'
        })
        return false
      }
      let t = this
      bakUpTable(selectes, isOpAll).then(res => {
        this.$Notice.success({
          title: '操作成功',
          desc: res.message,
          duration: 0
        })
      })
    },
  },
}
</script>
