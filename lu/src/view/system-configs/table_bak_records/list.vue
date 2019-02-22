<template>
<div>
  <Row :gutter="24">
    <Col :xs="2" :lg="2" class="hidden-mobile">
    <Button to="/dashboard/databases" type="primary">
      <Icon type="ios-arrow-back" /> 回到数据表页面
    </Button>
    </Col>
    <Col :xs="4" :lg="4" class="hidden-mobile">
    <Poptip confirm placement="right" title="确认要执行删除操作?" @on-ok="bakUpTableExcute(selectIds,false)" ok-text="确认" cancel-text="点错了">
      <Button type="warning" :loading="loadingBakBtn">
        <span v-if="!loadingBakBtn">{{ $t('destroy') }}</span>
        <span v-else>备份进行中...</span>
      </Button>
    </Poptip>
    </Col>
    <!-- <Col :xs="3" :lg="3">
    <Button type="primary" icon="ios-search" @click="getTableDataExcute(feeds.current_page)">{{ $t('search') }}</Button>
    </Col> -->
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
        <Poptip trigger="hover" title="备份的表">
          <Button>{{ row.first_table_name }}</Button>
          <div  slot="content">
            sdfsdf
          </div>
        </Poptip>
      </template>
      <template slot-scope="{ row, index }" slot="action">
        <!-- <Button type="primary" size="small" style="margin-right: 5px" @click="tableButtonShowInfo(row,index)">优化</Button> -->
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
  destroy,
} from '@/api/table_bak_record'

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
      all_tables_num: 0,
      all_tables_length: 0,
      selectIds: '',
      loadingBakBtn: false,
      loadingOptimizeBtn: false,
      loadingRepairBtn: false,
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
          title: '操作用户',
          key: 'user_id',
          minWidth: 100,
        },
        {
          title: '文件大小',
          key: 'file_size',
          minWidth: 80,
          sortable: 'customer'
        }, {
          title: '文件数量',
          key: 'file_num',
          minWidth: 20,
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
    onSelectionChange: function(selection) {
      this.selectIds = ''
      for (let index in selection) {
        this.selectIds += ',' + selection[index].Name
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
      this.loadingBakBtn = true
      let t = this
      destroyManyTableBakRecord(selectes, isOpAll).then(res => {
        this.$Notice.success({
          title: '操作成功',
          desc: res.message,
          duration: 0
        })
        this.loadingBakBtn = false
      }).catch((err) => {
        this.loadingBakBtn = false
      })
    },
  },
}
</script>
