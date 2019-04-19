<template>
<div>
  <Row :gutter="24">
    <Col :xs="4" :lg="8">
    <Button type="success" icon="plus" @click="addBtn()">{{ $t('add') }}</Button>
    </Col>
    <Col :xs="3" :lg="3">
      <Select v-model="searchForm.name" placeholder="app名称">
        <Option value="" key="">全部</Option>
        <Option v-for="(item,key) in tableStatus.name" :value="key" :key="key">{{ item }}</Option>
      </Select>
    </Col>
    <Col :xs="3" :lg="3">
      <Select v-model="searchForm.mobile_os" placeholder="系统">
        <Option value="" key="">全部</Option>
        <Option v-for="(item,key) in tableStatus.mobile_os" :value="key" :key="key">{{ item }}</Option>
      </Select>
    </Col>
    <Col :xs="3" :lg="3">
      <Select v-model="searchForm.is_force_update" placeholder="是否强制更新">
        <Option value="" key="">全部</Option>
        <Option v-for="(item,key) in tableStatus.is_force_update" :value="key" :key="key">{{ item }}</Option>
      </Select>
    </Col>
    <Col :xs="5" :lg="3" class="hidden-mobile">
      <Input icon="search" placeholder="请输入版本号..." v-model="searchForm.version_sn"></Input>
    </Col>
    <Col :xs="3" :lg="3">
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
    <Table  border :columns="columns" :data="dataList" @on-sort-change='onSortChange'>
      <template slot-scope="{ row, index }" slot="weight">
        <table-edit table="carousels" column="weight" :id="row.id" :value="row.weight" :index="index"></table-edit>
      </template>

      <template slot-scope="{ row, index }" slot="name">
        <div v-if="row.package_url" class="text-center">
          <a :href="row.package_url" target="black_">下载更新包</a>
        </div>
      </template>

      <template slot-scope="{ row, index }" slot="name">
          {{tableStatus.name[row.name]}}
      </template>

      <template slot-scope="{ row, index }" slot="mobile_os">
          {{tableStatus.mobile_os[row.mobile_os]}}
      </template>

      <template slot-scope="{ row, index }" slot="action">
        <Button type="success" size="small" style="margin-right: 5px" @click="tableButtonEdit(row,index)">{{ $t('edit') }}</Button>
        <Button type="primary" size="small" style="margin-right: 5px" @click="tableButtonShowInfo(row,index)">{{ $t('show_info') }}</Button>
      </template>
    </Table>
  </Row>

  <add-component v-if='addModal.show' :table-status="tableStatus"  @on-add-modal-success='getTableDataExcute' @on-add-modal-hide="addModalHide"></add-component>
  <edit-component v-if='editModal.show' :table-status="tableStatus"  :modal-id='editModal.id' @on-edit-modal-success='getTableDataExcute' @on-edit-modal-hide="editModalHide"> </edit-component>
  <show-info v-if='showInfoModal.show' :info='showInfoModal.info' @show-modal-close="showModalClose"></show-info>

</div>
</template>

<script>
import AddComponent from './components/add'
import EditComponent from './components/edit'
import ShowInfo from './components/show-info'

import {
  getTableData
} from '@/api/app_version'
import {
  oneOf
} from '@/libs/tools'
import {
  getTableStatus
} from '@/api/common'

export default {
  components: {
    AddComponent,
    EditComponent,
    ShowInfo
  },
  data () {
    return {
      searchForm: {
        order_by: 'id,desc',
        mobile_os: '',
        is_force_update: '',
        name: ''
      },
      tableStatus: {
        mobile_os: [],
        is_force_update: [],
        name: []
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
        title: 'app',
        minWidth: 100,
        slot: 'name'
      }, {
        title: '系统',
        minWidth: 50,
        slot: 'mobile_os'
      }, {
        title: '是否强更',
        minWidth: 50,
        key: 'is_force_update'
      }, {
        title: '版本号',
        minWidth: 100,
        sortable: 'customer',
        key: 'version_sn'
      }, {
        title: '描述',
        key: 'description',
        minWidth: 100,
        tooltip: true
      },
      {
        title: '发布时间',
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
    t.getTableStatusExcute('app_versions')
  },
  methods: {
    getTableStatusExcute (params) {
      let t = this
      getTableStatus(params).then(res => {
        t.tableStatus.mobile_os = res.data.mobile_os
        t.tableStatus.is_force_update = res.data.is_force_update
        t.tableStatus.name = res.data.name
        t.getTableDataExcute()
      })
    },
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
    tableButtonShowInfo (row, index) {
      this.showInfoModal.show = true
      this.showInfoModal.info = row
    },
    showModalClose () {
      this.showInfoModal.show = false
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
    }
  }
}
</script>
