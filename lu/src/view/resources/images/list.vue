
<template>
<div>
  <Row :gutter="24">
    <Col :xs="6" :lg="6" class="hidden-mobile">
    <Input icon="search" placeholder="请输入文件名称..." v-model="searchForm.original_name" />
    </Col>
    <Col :xs="5" :lg="4">
    </Col>
    <Col :xs="5" :lg="4">
    <Select v-model="searchForm.type" placeholder="请选择附件归类">
      <Option value="" key="">全部</Option>
      <Option v-for="(item,key) in tableStatus.category" :value="key" :key="key">{{ item }}</Option>
    </Select>
    </Col>
    <Col :xs="1" :lg="2">
    <Button type="primary" icon="ios-search" @click="getTableDataExcute(feeds.current_page)">Search</Button>
    </Col>
  </Row>
  <br>

  <Row>
    <div class="demo-spin-container" v-if="tableLoading">
      <Spin fix>
        <Icon type="load-c" size=18 class="spin-icon-load"></Icon>
        <div>加载中...</div>
      </Spin>
    </div>
    <Scroll :on-reach-edge="handleReachEdge" :height="690" :loading-text="scroll_text" :distance-to-edge="10">
      <div class="galley-image-photo-list">
        <ul class="pictures  row l-hide" ref="galley-photo">
          <li v-for="(item, key) in feeds.data">
            <img :data-original="item.domain + '/' + item.link_path + '/' + item.storage_name" :src="item.domain + '/' + item.link_path + '/' + item.storage_name" alt="">
            <div class="photo-text">
              <span class="photo-size">{{ item.size }} kb</span>
              <span class="photo-date">{{ item.created_at.substring(0,10) }}</span>
            </div>
            <span class="photo-name">{{ item.original_name}}</span>
          </li>
        </ul>
      </div>
      <div v-if="!feeds.data">
        暂无数据
      </div>
    </Scroll>
  </Row>
</div>
</template>


<script>
import './style.less'
import Viewer from 'viewerjs';
import 'viewerjs/dist/viewer.css';
import {
  getTableStatus,
} from '@/api/common'

import {
  getTableData,
  deleteAttachment
} from '@/api/attachment'

export default {
  data() {
    return {
      searchForm: {
        order_by: 'id,desc',
        type: '',
        mime_type: "image",
        original_name: ''
      },
      tableLoading: false,
      tableStatus: {
        category: [],
      },
      feeds: {
        data: [],
        total: 0,
        current_page: 1,
        per_page: 18,
        last_page: 0,
      },
      scroll_text: '拼命加载中...'
    }
  },
  mounted() {
    let t = this
    t.getTableStatusExcute('attachments/category')
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
        const response_data = res.data
        t.tableStatus.category = response_data
      })
    },
    getTableDataExcute(to_page) {
      let t = this
      t.tableLoading = true
      t.feeds.current_page = to_page
      getTableData(to_page, t.feeds.per_page, t.searchForm).then(res => {
        t.feeds.data = res.data
        t.feeds.total = res.meta.total
        t.feeds.last_page = res.meta.last_page
        t.tableLoading = false
        this.ViewImage()
      }, function(error) {
        t.tableLoading = false
      })
    },
    onSortChange: function(data) {
      const order = data.column.key + ',' + data.order
      this.searchForm.order_by = order
      this.getTableDataExcute(this.feeds.current_page)
    },
    handleReachEdge(dir) {
      this.scroll_text = '拼命加载中...'
      return new Promise(resolve => {
        let new_page = this.feeds.current_page;
        if (dir > 0) {
          new_page = this.feeds.current_page - 1;
          if (new_page < 1) {
            this.scroll_text = '已经是第一页了'
            resolve();
            return;
          }
        } else {
          new_page = this.feeds.current_page + 1;
          if (new_page > this.feeds.last_page) {
            this.scroll_text = '已经是最后一页了'
            resolve();
            return;
          }
        }
        this.getTableDataExcute(new_page)
        resolve();
      });
    },
    deleteAttachmentExcute(attachment, key) {
      let t = this
      deleteAttachment(attachment).then(res => {
        t.feeds.data.splice(key, 1)
        t.$Notice.success({
          title: res.message
        })
      })
    },
    ViewImage() {
      this.$nextTick(() => {
        $(function() {
          $('.l-hide').click(function() {
            $('.l-show').removeAttr('id').addClass('l-hide').removeClass('l-show');
            $(this).attr('id', 'galley-photo');
            $(this).addClass('l-show');
            $(this).removeClass('l-hide');
            var galley = document.getElementById('galley-photo');
            var viewer = new Viewer(galley, {
              url: 'data-original',
              toolbar: {
                oneToOne: true,
                prev: function() {
                  viewer.prev(true);
                },
                play: true,
                next: function() {
                  viewer.next(true);
                },
                update: function() {

                },
                download: function() {
                  const a = document.createElement('a');

                  a.href = viewer.image.src;
                  a.download = viewer.image.alt;
                  document.body.appendChild(a);
                  a.click();
                  document.body.removeChild(a);
                },
              },
            });
          });
        });
      });
    },
  }
}
</script>
