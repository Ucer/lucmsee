
<template>
<div>
  <Row :gutter="24">
    <Col :xs="5" :lg="1">
    <Button type="success" icon="plus" @click="addBtn()">{{ $t('add') }}</Button>
    </Col>
    <Col :xs="3" :lg="3">
    <Select v-model="searchForm.article_category_id" filterable placeholder="请选择文章分类">
      <Option value="" key="">全部</Option>
      <Option v-for="(item,key) in articleCategories" :key="key" :value="item.id">{{ item.name }} </Option>
    </Select>
    </Col>
    <Col :xs="3" :lg="3">
    <Select v-model="searchForm.enable" placeholder="请选择状态">
      <Option value="" key="">全部</Option>
      <Option v-for="(item,key) in tableStatus.enable" :value="key" :key="key">{{ item }}</Option>
    </Select>
    </Col>
    <Col :xs="3" :lg="3">
    <Select v-model="searchForm.access_type" placeholder="访问权限">
      <Option value="" key="">全部</Option>
      <Option v-for="(item,key) in tableStatus.access_type" :value="key" :key="key">{{ item }}</Option>
    </Select>
    </Col>
    <Col :xs="3" :lg="3">
    <Select v-model="searchForm.recommend" placeholder="是否推荐">
      <Option value="" key="">全部</Option>
      <Option v-for="(item,key) in tableStatus.recommend" :value="key" :key="key">{{ item }}</Option>
    </Select>
    </Col>
    <Col :xs="3" :lg="3">
    <Select v-model="searchForm.top" placeholder="是否置顶">
      <Option value="" key="">全部</Option>
      <Option v-for="(item,key) in tableStatus.top" :value="key" :key="key">{{ item }}</Option>
    </Select>
    </Col>
    <Col :xs="5" :lg="3" class="hidden-mobile">
    <Input icon="search" placeholder="请输入文章标题搜索..." v-model="searchForm.title"></Input>
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
      <template slot-scope="{ row, index }" slot="article_category">
        {{ row.article_category.name }}
      </template>
      <template slot-scope="{ row, index }" slot="access_type">
        <Tag>{{ tableStatus.access_type[row.access_type]}}</Tag>
      </template>
      <template slot-scope="{ row, index }" slot="enable">
        <iSwitch :slot="'open'" type='primary' :value="row.enable === 'T'" @on-change="switchChange(row,index,'enable')"></iSwitch>
      </template>
      <template slot-scope="{ row, index }" slot="top">
        <iSwitch :slot="'open'" type='primary' :value="row.top === 'T'" @on-change="switchChange(row,index,'top')"></iSwitch>
      </template>
      <template slot-scope="{ row, index }" slot="recommend">
        <iSwitch :slot="'open'" type='primary' :value="row.recommend === 'T'" @on-change="switchChange(row,index,'recommend')"></iSwitch>
      </template>

      <template slot-scope="{ row, index }" slot="action">
        <Button type="success" size="small" style="margin-right: 5px" @click="tableButtonEdit(row,index)">{{ $t('edit') }}</Button>
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

  <add-component v-if='addModal.show' :articleCategories="articleCategories" :tableStatus_recommend="tableStatus.recommend" :tableStatus_top="tableStatus.top" :tableStatus_enable="tableStatus.enable" @on-add-modal-success='getTableDataExcute(feeds.current_page)'
    @on-add-modal-hide="addModalHide"></add-component>
  <edit-component v-if='editModal.show' :articleCategories="articleCategories" :tableStatus_recommend="tableStatus.recommend" :tableStatus_top="tableStatus.top" :tableStatus_enable="tableStatus.enable" :modal-id='editModal.id' @on-edit-modal-success='getTableDataExcute(feeds.current_page)'
    @on-edit-modal-hide="editModalHide"> </edit-component>
  <show-info v-if='showInfoModal.show' :info='showInfoModal.info' @show-modal-close="showModalClose"></show-info>
</div>
</template>

<script>
import {
  getTableData,
  destroy
} from '@/api/article'
// import AddComponent from './components/add'
// import EditComponent from './components/edit'
import AddComponent from './components/markdown_add'
import EditComponent from './components/markdown_edit'
import ShowInfo from './components/show-info'

import {
  getAllCategories
} from '@/api/article_category'
import {
  getTableStatus,
  switchEnable
} from '@/api/common'
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
        access_type: '',
        title: '',
        enable: '',
        top: '',
        recommend: ''
      },
      notRealySortKey: [],
      tableLoading: false,
      tableStatus: {
        access_type: [],
        enable: [],
        top: [],
        recommend: []
      },
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
      showInfoModal: {
        show: false,
        info: ''
      },
      articleCategories: [],
      columns: [{
        title: 'ID',
        key: 'id',
        sortable: 'customer',
        minWidth: 100
      },
      {
        title: '标题',
        key: 'title',
        minWidth: 80,
        tooltip: true
      },
      {
        title: '访问权限',
        slot: 'access_type',
        minWidth: 80
      },
      {
        title: '分类',
        minWidth: 90,
        slot: 'article_category',
        tooltip: true
      },
      {
        title: '启用状态',
        key: 'enable',
        minWidth: 100,
        slot: 'enable'
      },
      {
        title: '置顶',
        key: 'top',
        minWidth: 50,
        slot: 'top'
      },
      {
        title: '推荐',
        key: 'recommend',
        minWidth: 50,
        slot: 'recommend'
      },
      {
        title: '创建时间',
        key: 'created_at',
        sortable: 'customer',
        minWidth: 150
      }, {
        title: '修改时间',
        key: 'updated_at',
        sortable: true,
        minWidth: 150
      },
      {
        title: '操作',
        minWidth: 150,
        slot: 'action'
      }
      ]

    }
  },
  created () {
    let t = this
    t.getAllCategoriesExcute()
    t.getTableStatusExcute('articles')
  },
  computed: {},
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
        t.tableStatus.access_type = res.data.access_type
        t.tableStatus.enable = res.data.enable
        t.tableStatus.recommend = res.data.recommend
        t.tableStatus.top = res.data.top
        t.getTableDataExcute(t.feeds.current_page)
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
        t.globalFancybox()
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
    addModalHide () {
      this.addModal.show = false
    },
    editModalHide () {
      this.editModal.show = false
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
    showModalClose () {
      this.showInfoModal.show = false
    },
    switchChange: function (row, index, column) {
      let t = this
      let new_status = 'T'
      if (t.feeds.data[index][column] === 'T') {
        new_status = 'F'
      }
      switchEnable(t.feeds.data[index].id, 'articles_column_' + column, new_status).then(res => {
        t.feeds.data[index][column] = new_status
        t.$Notice.success({
          title: res.message
        })
      }).catch((err) => {
        t.getTableDataExcute(t.feeds.current_page)
      })
    },
    getAllCategoriesExcute () {
      let t = this
      getAllCategories().then(res => {
        t.articleCategories = res.data
      })
    }
  }
}
</script>
