
<template>
<div>
  <Row :gutter="24">
    <Col :xs="2" :lg="2">
    <Button type="success" icon="plus" @click="addBtn()">{{ $t('add') }}</Button>
    </Col>
    <Col :xs="3" :lg="3">
    <Select v-model="searchForm.enable" placeholder="请选择状态">
      <Option value="" key="">全部</Option>
      <Option v-for="(item,key) in tableStatus.enable" :value="key" :key="key">{{ item }}</Option>
    </Select>
    </Col>
    <Col :xs="3" :lg="3">
    <Select v-model="searchForm.group" placeholder="请选择配置分组">
      <Option value="" key="">全部</Option>
      <Option v-for="(item,key) in tableStatus.config_group" :value="key" :key="key">{{ item.title }}</Option>
    </Select>
    </Col>
    <Col :xs="6" :lg="3" class="hidden-mobile">
    <Input icon="search" placeholder="请输入标识或标题搜索..." v-model="searchForm.table_name_or_flag"></Input>
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
      <template slot-scope="{ row, index }" slot="config_group">
        {{ tableStatus.config_group[row.config_group]['title'] }}
      </template>
      <template slot-scope="{ row, index }" slot="action">
        <Button type="success" size="small" style="margin-right: 5px" @click="tableButtonEdit(row,index)">{{ $t('edit') }}</Button>
        <Poptip confirm :title="'您确定要删除ID为：' + row.id + ' 的记录？'" @on-ok="tableButtonDestroyOk(row,index)"> <Button type='error' size="small" style="margin-right: 5px">{{ $t('destroy')}}</Button> </Poptip>
      </template>
    </Table>
  </Row>

  <add-component :config_group="tableStatus.config_group" v-if='addModal.show' @on-add-modal-success='getTableDataExcute(feeds.current_page)' @on-add-modal-hide="addModalHide"></add-component>
  <edit-component :config_group="tableStatus.config_group" v-if='editModal.show' :modal-id='editModal.id' @on-edit-modal-success='getTableDataExcute(feeds.current_page)' @on-edit-modal-hide="editModalHide"> </edit-component>
</div>
</template>

<script>
import {
  getTableData,
  destroy,
  getGroup
} from '@/api/system_config'

import AddComponent from './components/add'
import EditComponent from './components/edit'
import InputHelper from '_c/common/input-helper'

export default {
  components: {
    AddComponent,
    EditComponent,
    InputHelper
  },
  data () {
    return {
      searchForm: {
        order_by: 'created_at,desc',
        table_name_or_flag: '',
        enable: '',
        group: ''
      },
      notRealySortKey: [],
      tableStatus: {
        enable: [],
        config_group: []
      },
      tableLoading: false,
      feeds: {
        data: [],
        total: 0,
        current_page: 1,
        per_page: 10
      },
      addBtn () {
        this.addModal.show = true
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
        title: '配置分组',
        minWidth: 60,
        slot: 'config_group'
      },
      {
        title: '配置标识',
        key: 'flag',
        minWidth: 100
      },
      {
        title: '配置标题',
        key: 'title',
        minWidth: 100
      }, {
        title: '配置值',
        key: 'value',
        minWidth: 150
      },
      {
        title: '创建时间',
        key: 'created_at',
        sortable: true,
        minWidth: 150
      }, {
        title: '修改时间',
        key: 'updated_at',
        sortable: true,
        minWidth: 150
      }, {
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
    t.getGroupExcute()
  },
  methods: {
    getGroupExcute () {
      let t = this
      getGroup(t.searchForm).then(res => {
        const response_data = res.data
        t.tableStatus.config_group = response_data.config_group
        t.tableStatus.enable = response_data.enable

        t.getTableDataExcute(t.feeds.current_page)
      }, function (error) {})
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
    addModalHide () {
      this.addModal.show = false
    },
    editModalHide () {
      this.editModal.show = false
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
    }
  }
}
</script>
