<style>
.ivu-table .first_node td {
  /* background-color: #2db7f5; */
  color: black;
}

.ivu-table .children_node td {
  /* background-color: #e2e8ea; */
  /* color: #2db7f5; */
}
</style>
<template>
<div id="privileges-users-list">
  <Row :gutter="24">
    <Col :xs="8" :lg="16">
    <Button type="success" icon="plus" @click="addBtn()">{{ $t('add') }}</Button>
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

    <Table editable size="small" :row-class-name="rowClassName" :columns="columns" :data="dataList" @on-sort-change='onSortChange'>

      <template slot-scope="{ row, index }" slot="name">
        <span v-html="row.name"></span>
      </template>
      <template slot-scope="{ row, index }" slot="weight">
        <table-edit table="article_categories" column="weight" :id="row.id" :value="row.weight" :index="index"></table-edit>
      </template>
      <template slot-scope="{ row, index }" slot="action">
        <Button type="success" size="small" style="margin-right: 5px" @click="tableButtonEdit(row,index)">{{ $t('edit') }}</Button>
        <Poptip confirm :title="'您确定要删除ID为：' + row.id + ' 的记录？'" @on-ok="tableButtonDestroyOk(row,index)"> <Button type='error' size="small" style="margin-right: 5px">{{ $t('destroy')}}</Button> </Poptip>
      </template>
    </Table>

  </Row>

  <add-component v-if='addModal.show' :tableStatus_enable="tableStatus.enable" @on-add-modal-success='getTableDataExcute' @on-add-modal-hide="addModalHide"></add-component>
  <edit-component v-if='editModal.show' :tableStatus_enable="tableStatus.enable" :modal-id='editModal.id' @on-edit-modal-success='getTableDataExcute' @on-edit-modal-hide="editModalHide"> </edit-component>

</div>
</template>

<script>
import AddComponent from './components/add'
import EditComponent from './components/edit'
import TableEdit from '_c/common/table-edit.vue'

import {
  getTableData,
  destroy
} from '@/api/article_category'


import {
  getTableStatus
} from '@/api/common'

export default {
  components: {
    TableEdit,
    AddComponent,
    EditComponent
  },
  data() {
    return {
      searchForm: {
        order_by: 'weight,asc'
      },
      notRealySortKey:[],
      tableStatus: {
        enable: []
      },
      tableLoading: true,
      dataList: [],
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
        minWidth: 100,
      }, {
        title: '分类名称',
        minWidth: 150,
        slot: 'name'
      }, {
        title: 'pid',
        key: 'pid',
        minWidth: 100,
      }, {
        title: '分类描述',
        key: 'description',
        minWidth: 150,
      }, {
        title: '排序',
        key: 'weight',
        minWidth: 50,
        sortable: 'customer',
        slot: 'weight'
      }, {
        title: '创建时间',
        key: 'created_at',
        minWidth: 150,
      }, {
        title: '更新时间',
        key: 'created_at',
        minWidth: 150
      }, {
        title: '操作',
        minWidth: 200,
        slot: 'action'
      }]
    }
  },
  created() {
    let t = this
    t.getTableStatusExcute('article_categories/enable')
  },
  methods: {
    getTableStatusExcute(params) {
      let t = this
      getTableStatus(params).then(res => {
        t.tableStatus.enable = res.data
        t.getTableDataExcute()
      })
    },
    getTableDataExcute() {
      let t = this
      t.loading = true
      getTableData(t.searchForm).then(res => {
        const response_data = res.data
        t.dataList = response_data
        t.tableLoading = false
      }, function(error) {
        t.tableLoading = false
      })
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
    onSortChange: function(data) {
      const order = data.column.key + ',' + data.order
      if (oneOf(data.column.key, this.notRealySortKey)) {

      } else {
        this.searchForm.order_by = order
        this.getTableDataExcute(this.feeds.current_page)
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
    rowClassName(row, index) {
      if (row.pid < 1) {
        return 'first_node';
      } else {
        return 'children_node';
      }
      return '';
    },
  }
}
</script>
