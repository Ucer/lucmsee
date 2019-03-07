
<template>
<div>
  <Row :gutter="24">
    <Col :xs="6" :lg="6">
    <Button type="success" icon="plus" @click="addBtn()">{{ $t('add') }}</Button>
    </Col>
    <Col :xs="3" :lg="3">
    <Select v-model="searchForm.article_category_id" placeholder="请选择文章分类">
      <Option value="" key="">全部</Option>
      <Option v-for="(item,key) in articleCategories" :value="item.id">{{ item.name }} </Option>
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
      <template slot-scope="{ row, index }" slot="enable">
        <iSwitch :slot="'open'" type='primary' :value="row.enable === 'T'" @on-change="switchChange(row,index)"></iSwitch>
      </template>

      <template slot-scope="{ row, index }" slot="action">
        <Poptip confirm :title="'您确定要删除ID为：' + row.id + ' 的记录？'" @on-ok="tableButtonDestroyOk(row,index)"> <Button type='error' size="small" style="margin-right: 5px">{{ $t('destroy')}}</Button> </Poptip>
      </template>
    </Table>
    <div style="margin: 10px;overflow: hidden">
      <div style="float: right;">
        <Page :total="feeds.total" :current="feeds.current_page" :page-size="feeds.per_page" class="paging" show-elevator show-total show-sizer @on-change="handleOnPageChange" @on-page-size-change='onPageSizeChange'></Page>
      </div>
    </div>
  </Row>

  <add-component v-if='addModal.show' :tableStatus_is_admin="tableStatus.is_admin" @on-add-modal-success='getTableDataExcute(feeds.current_page)' @on-add-modal-hide="addModalHide"></add-component>
  <edit-component v-if='editModal.show' :tableStatus_is_admin="tableStatus.is_admin" :modal-id='editModal.id' @on-edit-modal-success='getTableDataExcute(feeds.current_page)' @on-edit-modal-hide="editModalHide"> </edit-component>
</div>
</template>

<script>
import {
  getTableData,
  destroy
} from '@/api/article'
import AddComponent from './components/add'
import EditComponent from './components/edit'


import {
  getAllCategories
} from '@/api/article_category'
import {
  getTableStatus
} from '@/api/common'
import {
  getTableStatus,
  switchEnable
} from '@/api/common'


export default {
  components: {
    AddComponent,
    EditComponent
  },
  data() {
    return {
      searchForm: {
        order_by: 'created_at,desc',
        access_type: '',
        title: '',
        enable: '',
        top: '',
        recommend: '',
      },
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
      addBtn() {
        this.addModal.show = true
      },
      addModal: {
        show: false
      },
      editModal: {
        show: false,
        id: 0
      },
      articleCategories: [],
      columns: [{
          title: 'ID',
          key: 'id',
          sortable: 'customer',
          minWidth: 100,
        },
        {
          title: '标题',
          key: 'title',
          minWidth: 80
        },
        {
          title: '分类',
          minWidth: 90,
          slot: 'article_category'
},
        {
          title: '启用状态',
          key: 'enable',
          minWidth: 80,
          slot: 'enable'
        },
        {
          title: '创建时间',
          key: 'created_at',
          sortable: 'customer',
          minWidth: 150,
        }, {
          title: '修改时间',
          key: 'updated_at',
          sortable: true,
          minWidth: 150,
        },
        {
          title: '操作',
          minWidth: 50,
          slot: 'action'
        }
      ],

    }
  },
  created() {
    let t = this
    t.getAllCategoriesExcute()
    t.getTableStatusExcute('articles')
  },
  computed: {},
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
        t.getTableDataExcute(t.feeds.current_page)
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
    addModalHide() {
      this.addModal.show = false
    },
    editModalHide() {
      this.editModal.show = false
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
    getAllCategoriesExcute() {
      let t = this
      getAllCategories().then(res => {
        t.articleCategories = res.data
        this.getInfoByIdExcute()
      })
    },
  }
}
</script>
