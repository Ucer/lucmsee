<template>
<div>
  <Row :gutter="24">
    <Col :xs="7" :lg="11">
    <Button type="success" icon="plus" @click="addBtn()">{{ $t('add') }}</Button>
    </Col>
    <Col :xs="6" :lg="3" class="hidden-mobile">
    <Input icon="search" @on-enter="getTableDataExcute(feeds.current_page)" placeholder="请输入表名搜索..." v-model="searchForm.table_name"></Input>
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
        <Button type="primary" size="small" style="margin-right: 5px" @click="tableButtonShowInfo(row,index)">{{ $t('show_info') }}</Button>
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

  <show-info v-if='showInfoModal.show' :info='showInfoModal.info' @show-modal-close="showModalClose"></show-info>
  <add-component v-if='addModal.show' @on-add-modal-success='getTableDataExcute(feeds.current_page)' @on-add-modal-hide="addModalHide"></add-component>
  <edit-component v-if='editModal.show' :modal-id='editModal.id' @on-edit-modal-success='getTableDataExcute(feeds.current_page)' @on-edit-modal-hide="editModalHide"> </edit-component>

</div>
</template>

<script>
import AddComponent from './components/add'
import EditComponent from './components/edit'

import ShowInfo from './components/show-info'

import {
  getTableData,
  destroy
} from '@/api/table'
import {
  oneOf
} from '@/libs/tools'

export default {
  components: {
    AddComponent,
    EditComponent,
    ShowInfo
  },
  data () {
    return {
      searchForm: {
        order_by: 'created_at,desc',
        table_name: '',
        column: ''
      },
      notRealySortKey: ['map_count'],
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
      columns: [{
        title: 'ID',
        key: 'id',
        sortable: 'customer',
        minWidth: 100
      },
      {
        title: '表名',
        key: 'table_name',
        minWidth: 100,
        className: 'table-column-style-table-name'
      },
      {
        title: '表中文名',
        key: 'table_name_cn',
        className: 'table-column-style-table-name-cn',
        minWidth: 150
      }, {
        title: '字典数量',
        key: 'map_count',
        sortable: true,
        minWidth: 50
      },
      {
        title: '操作',
        key: '',
        minWidth: 200,
        slot: 'action'
      }, {
        title: '备注',
        key: 'remark',
        minWidth: 150
      },
      {
        title: '修改时间',
        key: 'updated_at',
        sortable: 'customer',
        minWidth: 150
      }
      ]

    }
  },
  created () {
    let t = this
    t.getTableDataExcute(t.feeds.current_page)
  },
  methods: {
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
      if (oneOf(data.column.key, this.notRealySortKey)) {

      } else {
        this.searchForm.order_by = order
        this.getTableDataExcute(this.feeds.current_page)
      }
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
    tableButtonShowInfo (row, index) {
      this.showInfoModal.show = true
      this.showInfoModal.info = row
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
    showModalClose () {
      this.showInfoModal.show = false
    }
  }
}
</script>
