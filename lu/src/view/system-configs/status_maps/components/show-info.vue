<style>
.ivu-table .table-column-style-status-description {
  background-color: #b1896f;
  color: #fff;
}
.ivu-table .table-column-style-status-code {
  background-color: #2e5197;
  color: #fff;
}
</style>
<template>
<div>
  <Drawer :closable="true" v-model="show" @on-close='closed' title="表状态字典" :width="platformIsPc?60:80">
    <p class="drawer-title"><span style="font-weight:800">「{{ info.table_name }}」</span>表状态字典：</p>
    <div class="drawer-profile">
      <Row :gutter="24">
        <Col :xs="2" :lg="2">
        <Button type="success" icon="plus" @click="addBtn()">{{ $t('add') }}</Button>
        </Col>
        <Col :xs="6" :lg="6" class="hidden-mobile">
        <Input icon="search" placeholder="请输入表名搜索..." v-model="searchForm.column" ></Input>
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
        <Table size='small' :columns="columns" :data="feeds.data" @on-sort-change='onSortChange'>
          <template slot-scope="{ row, index }" slot="action">
        <Button type="success" size="small" style="margin-right: 5px" @click="tableButtonEdit(row,index)">{{ $t('edit') }}</Button>
        <Poptip confirm :title="'您确定要删除ID为：' + row.id + ' 的记录？'" @on-ok="tableButtonDestroyOk(row,index)"> <Button type='error' size="small" style="margin-right: 5px">{{ $t('destroy')}}</Button> </Poptip>
      </template>
        </Table>
        <!-- <div style="margin: 10px;overflow: hidden">
      <div style="float: right;">
        <Page :total="feeds.total" :current="feeds.current_page" :page-size="feeds.per_page" class="paging" show-elevator show-total show-sizer @on-change="handleOnPageChange" @on-page-size-change='onPageSizeChange'></Page>
      </div>
    </div> -->
      </Row>

      <add-component :table-name="info.table_name" v-if='addModal.show' @on-add-modal-success='getTableDataExcute(feeds.current_page)' @on-add-modal-hide="addModalHide"></add-component>
      <edit-component v-if='editModal.show' :modal-id='editModal.id' @on-edit-modal-success='getTableDataExcute(feeds.current_page)' @on-edit-modal-hide="editModalHide"> </edit-component>
    </div>
  </Drawer>
</div>
</template>
<script>
import AddComponent from './add-status_map'
import EditComponent from './edit-status_map'

import {
  getTableData,
  destroy
} from '@/api/status_map'
export default {
  props: {
    info: {
      default: ''
    }
  },
  components: {
    AddComponent,
    EditComponent
  },
  data () {
    return {
      show: true,
      agreement: '',
      spinLoading: true,
      searchForm: {
        order_by: 'column,asc',
        table_name: this.info.table_name
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
      columns: [{
        title: 'ID',
        key: 'id',
        sortable: 'customer',
        minWidth: 100
      },
      {
        title: '字段名',
        key: 'column',
        minWidth: 100
      },
      {
        title: '状态码',
        key: 'status_code',
        minWidth: 80,
        className: 'table-column-style-status-code'
      },
      {
        title: '状态描述',
        key: 'status_description',
        minWidth: 100,
        className: 'table-column-style-status-description'
      },
      {
        title: '备注',
        key: 'remark',
        minWidth: 100
      },
      {
        title: '创建时间',
        key: 'created_at',
        sortable: 'customer',
        minWidth: 100
      },
      {
        title: '修改时间',
        key: 'updated_at',
        sortable: 'customer',
        minWidth: 100
      },
      {
        title: '操作',
        key: '',
        minWidth: 100,
        slot: 'action'
      }
      ]
    }
  },
  created () {
    let t = this
    console.log(t.info.table_name)
    t.searchForm.table_name = t.info.table_name
    t.getTableDataExcute(t.feeds.current_page)
  },
  computed: {
    platformIsPc: function () {
      return this.globalPlatformType() == 'pc'
    }
  },
  methods: {
    closed () {
      this.show = false
      this.$emit('show-modal-close')
    },
    getTableDataExcute (to_page) {
      let t = this
      t.tableLoading = true
      t.feeds.current_page = to_page
      getTableData(to_page, t.feeds.per_page, t.searchForm).then(res => {
        t.feeds.data = res.data
        t.feeds.total = 0
        t.tableLoading = false
      }, function (error) {
        t.tableLoading = false
      })
    },
    onSortChange: function (data) {
      const order = data.column.key + ',' + data.order
      this.searchForm.order_by = order
      this.getTableDataExcute(this.feeds.current_page)
    },
    tableButtonEdit (row, index) {
      this.editModal.show = true
      this.editModal.id = row.id
    },
    tableButtonDestroyOk (row, index) {
      let t = this
      destroy(row.id).then(res => {
        t.feeds.data.splice(index, 1)
        t.$Notice.success({
          title: res.message
        })
      })
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
    cancelRoleModal () {
      let t = this
      t.roleModal.show = false
      t.roleModal.saveLoading = false
    }
  }
}
</script>
