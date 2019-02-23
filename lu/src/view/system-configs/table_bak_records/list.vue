<template>
<div>
  <Row :gutter="24">
    <Col :xs="2" :lg="2" class="hidden-mobile">
    <Button to="/dashboard/databases" type="primary">
      <Icon type="ios-arrow-back" /> 回到数据表页面
    </Button>
    </Col>
    <Col :xs="4" :lg="4" class="hidden-mobile">
    <Poptip confirm placement="right" title="确认要执行删除操作?" @on-ok="destroyManyTableBakRecordExcute(selectIds,false)" ok-text="确认" cancel-text="点错了">
      <Button type="warning">
        {{ $t('destroy') }}
      </Button>
    </Poptip>
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
      <template slot-scope="{ row, index }" slot="bak_tables_name">
        <Poptip word-wrap width="600" placement="bottom" trigger="hover" title="备份的表" slot="content">
          <Button>{{ row.first_table_name }}..</Button>
          <div slot="content">
            <span v-for="item,key in row.bak_tables_name">
              <Tag type="border" color="success" v-if="key%2 ==0">{{ item }}</Tag>
              <Tag type="border" color="error" v-else>{{ item }}</Tag>
            </span>
          </div>
        </Poptip>
      </template>
      <template slot-scope="{ row, index }" slot="action">
        <Button type="primary" size="small" style="margin-right: 5px" @click="tableBakSqlFileDownloadExcute(row,index)" :loading="loadingDownloadBtn === row.id">
          <span v-if="loadingDownloadBtn === row.id">下载中...</span>
          <span v-else>{{ $t('download') }}</span>
        </Button>
      </template>
    </Table>

    <div style="margin: 10px;overflow: hidden">
      <div style="float: right;">
        <Page :total="feeds.total" :current="feeds.current_page" :page-size="feeds.per_page" class="paging" show-elevator show-total show-sizer @on-change="handleOnPageChange" @on-page-size-change='onPageSizeChange'></Page>
      </div>
    </div>
  </Row>


</div>
</template>

<script>
import {
  getTableData,
  destroyManyTableBakRecord,
  tableBakSqlFileDownload
} from '@/api/table_bak_record'

import {
  getNowDateTimeWeek
} from '@/libs/tools.js'
export default {
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
      selectIds: '',
      loadingDownloadBtn: 0,
      bak_data_rows: 0,
      columns: [{
          type: 'selection',
          width: 60,
          align: 'center'
        },
        {
          title: 'ID',
          key: 'id',
          sortable: 'customer',
          minWidth: 100,
        },
        {
          title: '备份的表',
          minWidth: 100,
          slot: 'bak_tables_name'
        },
        {
          title: '操作用户ID',
          key: 'user_id',
          minWidth: 100,
        },
        {
          title: '文件大小',
          key: 'file_size',
          minWidth: 80,
          sortable: 'customer'
        }, {
          title: '备份产生文件数量',
          key: 'file_num',
          minWidth: 60,
        },
        {
          title: '创建时间',
          key: 'created_at',
          sortable: 'customer',
          minWidth: 150,
        }, {
          title: '修改时间',
          key: 'updated_at',
          sortable: 'customer',
          minWidth: 150,
        },
        {
          title: '操作',
          key: '',
          minWidth: 200,
          slot: 'action'
        }
      ],

    }
  },
  created() {
    let t = this
    t.getTableDataExcute(t.feeds.current_page)
  },
  methods: {
    handleOnPageChange: function(to_page) {
      this.getTableDataExcute(to_page)
    },
    onPageSizeChange: function(per_page) {
      this.feeds.per_page = per_page
      this.getTableDataExcute(this.feeds.current_page)
    },
    getTableDataExcute(to_page) {
      let t = this
      t.tableLoading = true
      t.feeds.current_page = to_page
      getTableData(to_page, t.feeds.per_page, t.searchForm).then(res => {
        t.feeds.data = res.data
        t.feeds.total = res.meta.total
        t.tableLoading = false
      }, function(error) {
        t.tableLoading = false
      })

    },
    onSortChange: function(data) {
      const order = data.column.key + ',' + data.order
      this.searchForm.order_by = order
      this.getTableDataExcute(this.feeds.current_page)
    },
    // tableButtonDestroyOk(row, index) {
    //   let t = this
    //   destroy(row.id).then(res => {
    //     t.feeds.data.splice(index, 1)
    //     t.$Notice.success({
    //       title: res.message
    //     })
    //   })
    // },
    onSelectionChange: function(selection) {
      this.selectIds = ''
      for (let index in selection) {
        this.selectIds += ',' + selection[index].id
      }
    },
    destroyManyTableBakRecordExcute(selectes, isOpAll) {
      if (isOpAll === false && !selectes) {
        this.$Notice.error({
          title: '出错了',
          desc: '请先选择要操作的项'
        })
        return false
      }
      let t = this
      destroyManyTableBakRecord(selectes, isOpAll).then(res => {
        t.$Notice.success({
          title: res.message
        })
        t.getTableDataExcute(t.feeds.current_page)
      })
    },
    tableBakSqlFileDownloadExcute(row, index) {
      let t = this
      t.loadingDownloadBtn = row.id
      tableBakSqlFileDownload(row.id).then(res => {
        const content = res
        const blob = new Blob([content])
        let now_data_time_week = getNowDateTimeWeek('-', '');
        const fileName = now_data_time_week[0] + '' + now_data_time_week[1] + '.sql'
        if ('download' in document.createElement('a')) { // 非IE下载
          const elink = document.createElement('a')
          elink.download = fileName
          elink.style.display = 'none'
          elink.href = URL.createObjectURL(blob)
          document.body.appendChild(elink)
          elink.click()
          URL.revokeObjectURL(elink.href) // 释放URL 对象
          document.body.removeChild(elink)
        } else { // IE10+下载
          navigator.msSaveBlob(blob, fileName)
        }

        t.loadingDownloadBtn = 0
      })
    },
  },
}
</script>
