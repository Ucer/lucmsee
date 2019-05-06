
<template>
<div>
  <Row :gutter="24">
    <Col :xs="7" :lg="11">
    <ButtonGroup shape="circle">
      <Button @click="uploadBtnGroup('Image')">上传图片</Button>
      <Button @click="uploadBtnGroup('File')">文件</Button>
      <Button @click="uploadBtnGroup('Video')">视频</Button>
    </ButtonGroup>
    </Col>
    <Col :xs="3" :lg="3">
    <Select v-model="searchForm.file_type" placeholder="请选择文件类型">
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
    <Input icon="search" placeholder="请输入文件名搜索..." v-model="searchForm.original_name"></Input>
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
      <template slot-scope="{ row, index }" slot="download">
        <div v-if='attachmentIsImage(row)'>
          <img style="max-height:50px;margin-top:1%" :src="getAttachmentUrl(row)" :href="getAttachmentUrl(row)" class="fancybox" :title="row.original_name" alt="头像" />
        </div>
        <div v-else>
          <a :href="getAttachmentUrl(row)" target="black_">下载附件</a>
        </div>
      </template>

      <template slot-scope="{ row, index }" slot="user_id">
        {{ row.user_id }}
      </template>

      <template slot-scope="{ row, index }" slot="category">
        {{ row.category }}
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
  <upload-image-component v-if='uploadImageModal.show' @on-upload-image-modal-hide="uploadImageModalHide"></upload-image-component>
  <upload-file-component v-if='uploadFileModal.show' @on-upload-file-modal-hide="uploadFileModalHide"></upload-file-component>
  <upload-video-component v-if='uploadVideoModal.show' @on-upload-video-modal-hide="uploadVideoModalHide"></upload-video-component>

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

import uploadImageComponent from './components/upload-image'
import uploadFileComponent from './components/upload-file'
import uploadVideoComponent from './components/upload-video'
export default {
  components: {
    uploadImageComponent,
    uploadFileComponent,
    uploadVideoComponent
  },
  data () {
    return {
      searchForm: {
        order_by: 'created_at,desc',
        file_type: '',
        category: ''
      },
      notRealySortKey: [],
      tableLoading: false,
      tableStatus: {
        file_type: [],
        category: []
      },
      feeds: {
        data: [],
        total: 0,
        current_page: 1,
        per_page: 10
      },
      uploadImageModal: {
        show: false
      },
      uploadFileModal: {
        show: false
      },
      uploadVideoModal: {
        show: false
      },
      columns: [{
        title: 'ID',
        key: 'id',
        sortable: 'customer',
        minWidth: 100
      },
      {
        title: '上传者',
        minWidth: 80,
        slot: 'user_id'
      },
      {
        title: '下载',
        minWidth: 60,
        slot: 'download'
      },
      {
        title: '原文件名',
        key: 'original_name',
        minWidth: 80
      },
      {
        title: '存储名称',
        minWidth: 120,
        key: 'storage_name'
      },
      {
        title: 'mimeType',
        minWidth: 80,
        key: 'mime_type',
        sortable: 'customer'
      },
      {
        title: '文件归类',
        minWidth: 80,
        key: 'category',
        sortable: 'customer',
        slot: 'category'
      }, {
        title: '大小',
        key: 'size',
        sortable: 'customer',
        minWidth: 80
      },
      {
        title: '上传时间',
        key: 'created_at',
        sortable: 'customer',
        minWidth: 150
      },
      {
        title: '操作',
        minWidth: 50,
        slot: 'action'
      }
      ]

    }
  },
  created () {
    let t = this
    t.getTableStatusExcute('attachments')
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
        t.tableStatus.file_type = res.data.file_type
        t.tableStatus.category = res.data.category
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
    tableButtonDestroyOk (row, index) {
      let t = this
      destroy(row.id).then(res => {
        t.feeds.data.splice(index, 1)
        t.$Notice.success({
          title: res.message
        })
      })
    },
    uploadBtnGroup (type) {
      this['upload' + type + 'Modal'].show = true
    },
    uploadImageModalHide () {
      this.uploadImageModal.show = false
      this.getTableStatusExcute(this.feeds.current_page)
    },
    uploadFileModalHide () {
      this.uploadFileModal.show = false
      this.getTableStatusExcute(this.feeds.current_page)
    },
    uploadVideoModalHide () {
      this.uploadVideoModal.show = false
      this.getTableStatusExcute(this.feeds.current_page)
    },
    getAttachmentUrl (row) {
      return row.domain + '/' + row.link_path + '/' + row.storage_name
    },
    attachmentIsImage (row) {
      let min_type = row.mime_type
      return min_type.indexOf('image') !== -1
    }
  }
}
</script>
