
<template>
<div>
  <Row :gutter="24">
    <Col :xs="4" :lg="2" class="hidden-mobile">
    <!-- <a :href='exportExcel' target="_blank"><Button icon="md-download">导出文件</Button></a> -->
    <Button @click="exportExcelLogExcute" icon="md-download">导出文件</Button>
    </Col>
    <Col :xs="6" :lg="6" class="hidden-mobile">
    <DatePicker @on-change="searchTimeStart" type="datetime" format="yyyy-MM-dd HH:mm" placeholder="开始时间"></DatePicker>-
    <DatePicker @on-change="searchTimeEnd" type="datetime" format="yyyy-MM-dd HH:mm" placeholder="结束时间"></DatePicker>
    </Col>
    <Col :xs="3" :lg="3">
    <Select v-model="searchForm.type" placeholder="请选择日志类型">
      <Option value="" key="">全部</Option>
      <Option v-for="(item,key) in tableStatus.type" :value="key" :key="key">{{ item }}</Option>
    </Select>
    </Col>
    <Col :xs="3" :lg="3">
    <Select v-model="searchForm.table_name" placeholder="请选择模型表" filterable>
      <Option value="" key="">全部</Option>
      <Option v-for="(item,key) in allTableList" :value="key" :key="key">{{ item.table_name_cn }}</Option>
    </Select>
    </Col>
    <Col :xs="6" :lg="3" class="hidden-mobile">
    <Input icon="search" placeholder="请输入操作人真实姓名..." v-model="searchForm.real_name"></Input>
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
    <Table border :columns="columns" :data="feeds.data" @on-sort-change='onSortChange'>

      <template slot-scope="{ row, index }" slot="user_id">
        <div class="text-center">
          {{ row.user.real_name }}
        </div>
      </template>

      <template slot-scope="{ row, index }" slot="table_name">
        {{ allTableList[row.table_name].table_name_cn }}
      </template>

      <template slot-scope="{ row, index }" slot="type">
        {{ tableStatus.type[row.type] }}
      </template>

      <template slot-scope="{ row, index }" slot="action">
        <Button type="primary" size="small" style="margin-right: 5px" @click="tableButtonShowInfo(row,index)">{{ $t('show_info') }}</Button>
        <Poptip confirm :title="'您确定要删除ID为：' + row.id + ' 的记录？'" @on-ok="tableButtonDestroyOk(row,index)"> <Button type='error' size="small" style="margin-right: 5px">{{ $t('destroy')}}</Button> </Poptip>
      </template>
    </Table>
    <div style="margin: 10px;overflow: hidden">
      <div style="float: right;">
        <Page :total="feeds.total" :current="feeds.current_page" :page-size="feeds.per_page" class="paging" show-elevator show-total show-sizer @on-change="handleOnPageChange" @on-page-size-change='onPageSizeChange'></Page>
      </div>
    </div>
  </Row>

  <show-info v-if='showInfoModal.show' :info='showInfoModal.info' @show-modal-close="showModalClose"></show-info>
</div>
</template>

<script>
import ShowInfo from './components/show-info'

import {
  getTableData,
  destroy
} from '@/api/log'

import {
  getAllTables
} from '@/api/table'
import {
  exportExcelLog
} from '@/api/excel_url'

import {
  getTableStatus
} from '@/api/common'

import { downloadFilePassUrl, oneOf } from '@/libs/tools'

export default {
  components: {
    ShowInfo
  },
  data () {
    return {
      searchForm: {
        order_by: 'created_at,desc',
        table_name: '',
        type: '',
        real_name: '', // 操作人真实姓名
        start_time: '',
        end_time: ''
      },
      allTableList: [],
      notRealySortKey: [],
      tableLoading: false,
      tableStatus: {
        enable: [],
        is_admin: []
      },
      feeds: {
        data: [],
        total: 0,
        current_page: 1,
        per_page: 10
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
        title: '操作人',
        minWidth: 100,
        slot: 'user_id'
      },
      {
        title: '日志类型',
        key: 'type',
        minWidth: 100,
        slot: 'type'
      },
      {
        title: '模型表',
        key: '',
        width: 80,
        slot: 'table_name'
      },
      {
        title: '操作人ip',
        key: 'ip',
        minWidth: 150
      },
      {
        title: '创建时间',
        key: 'created_at',
        minWidth: 150
      },
      {
        title: '操作',
        key: '',
        minWidth: 200,
        slot: 'action'
      }
      ]

    }
  },
  created () {
    let t = this
    t.getTableStatusExcute('logs/type')
    t.getAllTablesExcute()
  },
  computed: {
    // exportExcel() {
    //   return exportExcelLogUrl + '?search_data=' + JSON.stringify(this.searchForm)
    // }
  },
  methods: {
    handleOnPageChange: function (to_page) {
      this.getTableDataExcute(to_page)
    },
    onPageSizeChange: function (per_page) {
      this.feeds.per_page = per_page
      this.getTableDataExcute(this.feeds.current_page)
    },
    getTableStatusExcute (params) {
      let t = this
      getTableStatus(params).then(res => {
        t.tableStatus.type = res.data
        t.getTableDataExcute(t.feeds.current_page)
      })
    },
    getAllTablesExcute (params) {
      let t = this
      getAllTables('').then(res => {
        t.allTableList = res.data
      })
    },
    getTableDataExcute (to_page) {
      let t = this
      t.tableLoading = true
      t.feeds.current_page = to_page
      getTableData(to_page, t.feeds.per_page, t.searchForm).then(res => {
        t.feeds.data = res.data
        t.feeds.total = res.meta.total
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
    showModalClose () {
      this.showInfoModal.show = false
    },
    searchTimeStart: function (value, dateType) {
      this.searchForm.start_time = value
    },
    searchTimeEnd: function (value, dateType) {
      this.searchForm.end_time = value
    },
    exportExcelLogExcute () {
      let t = this
      t.tableLoading = true
      exportExcelLog().then(res => {
        let resData = res.data
        downloadFilePassUrl(resData.url)
        t.tableLoading = false
      }, function (error) {
        t.tableLoading = false
      })
    }
  }
}
</script>
