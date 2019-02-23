
<template>
<div>
  <Row :gutter="24">
    <Col :xs="7" :lg="11">
    <Button type="success" icon="plus" @click="addBtn()">{{ $t('add') }}</Button>
    </Col>
    <Col :xs="3" :lg="3">
    <Select v-model="searchForm.enable" placeholder="请选择文件类型">
      <Option value="" key="">全部</Option>
      <Option v-for="(item,key) in tableStatus.file_type" :value="key" :key="key">{{ item }}</Option>
    </Select>
    </Col>
    <Col :xs="3" :lg="3">
    <Select v-model="searchForm.category" filterable placeholder="请选择归类">
      <Option value="" key="">全部</Option>
      <Option v-for="(item,key) in tableStatus.category" :value="key" :key="key">{{ item }}</Option>
    </Select>
    </Col>
    <Col :xs="6" :lg="3" class="hidden-mobile">
    <Input icon="search" placeholder="请输入文件名搜索..." v-model="searchForm.original_name" />
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

      <template slot-scope="{ row, index }" slot="avatar">
        <div class="text-center">
          <img :src="row.avatar" v-if="row.avatar" class="fancybox" :href="row.avatar" tatle="头像" alt="头像" style="width:40px;height:40px">
          <span v-else>--</span>
        </div>
      </template>

      <template slot-scope="{ row, index }" slot="is_admin">
        <Tag v-if="row.is_admin === 'T'" :color="'green'">可登录</Tag>
        <Tag v-else :color="'red'">不可登录</Tag>
      </template>

      <template slot-scope="{ row, index }" slot="enable">
        <iSwitch :slot="'open'" type='primary' :value="row.enable === 'T'" @on-change="switchChange(row,index)"></iSwitch>
      </template>

      <template slot-scope="{ row, index }" slot="action">
        <Button type="success" size="small" style="margin-right: 5px" @click="tableButtonEdit(row,index)">{{ $t('edit') }}</Button>
        <Button type="info" size="small" style="margin-right: 5px" @click="tableButtonGiveUserRoles(row,index)">{{ $t('permission') }}</Button>
        <Poptip confirm :title="'您确定要删除ID为：' + row.id + ' 的记录？'" @on-ok="tableButtonDestroyOk(row,index)"> <Button type='error' size="small" style="margin-right: 5px">{{ $t('destroy')}}</Button> </Poptip>
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
  destroy
} from '@/api/attachment'


import {
  getTableStatus
} from '@/api/common'

export default {
  components: {},
  data() {
    return {
      searchForm: {
        order_by: 'created_at,desc'
      },
      tableLoading: false,
      tableStatus: {
        file_type: [],
      },
      feeds: {
        data: [],
        total: 0,
        current_page: 1,
        per_page: 10
      },
      columns: [{
          title: 'ID',
          key: 'id',
          sortable: 'customer',
          minWidth: 100,
        },
        {
          title: '上传者',
          minWidth: 90,
          slot: 'user_id'
        },
        {
          title: '文件原名称',
          key: 'original_name',
          minWidth: 80,
        },
        {
          title: '存储名称',
          minWidth: 120,
          key: 'storage_name'
        },
        {
          title: '文件类型/mime_type',
          minWidth: 140,
          slot: 'file_type_and_mine_type'
        },
        {
          title: '文件归类',
          minWidth: 80,
          slot: 'category'
        },
        {
          title: '上传时间',
          key: 'created_at',
          minWidth: 150,
        },
        {
          title: '操作',
          key: '',
          minWidth: 100,
          slot: 'action'
        }
      ],

    }
  },
  created() {
    let t = this
    t.getTableStatusExcute('attachments')
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
    getTableStatusExcute(params) {
      let t = this
      getTableStatus(params).then(res => {
        t.tableStatus.file_type = res.data.file_type
        t.tableStatus.category = res.data.category
      })
    },
    getTableDataExcute(to_page) {
      let t = this
      t.tableLoading = true
      t.feeds.current_page = to_page
      getTableData(to_page, t.feeds.per_page, t.searchForm).then(res => {
        t.feeds.data = res.data
        t.feeds.total = res.meta.total
        t.tableLoading = false
        t.globalFancybox()
      }, function(error) {
        t.tableLoading = false
      })

    },
    onSortChange: function(data) {
      const order = data.column.key + ',' + data.order
      this.searchForm.order_by = order
      this.getTableDataExcute(this.feeds.current_page)
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
    upLoadBtn() {
      this.addModal.show = true
    },
    addModalHide() {
      this.addModal.show = false
    }
  }
}
</script>
