<style>
.demo-upload-list {
  display: inline-block;
  width: 60px;
  height: 60px;
  text-align: center;
  line-height: 60px;
  border: 1px solid transparent;
  border-radius: 4px;
  overflow: hidden;
  background: #fff;
  position: relative;
  box-shadow: 0 1px 1px rgba(0, 0, 0, .2);
  margin-right: 4px;
}

.demo-upload-list img {
  width: 100%;
  height: 100%;
}

.demo-upload-list-cover {
  display: none;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0, 0, 0, .6);
}

.demo-upload-list:hover .demo-upload-list-cover {
  display: block;
}

.demo-upload-list-cover i {
  color: #fff;
  font-size: 20px;
  cursor: pointer;
  margin: 0 2px;
}

.fancybox {
  max-width: 100%
}

.ivu-upload {
  width: auto !important;
}
</style>
<template>
<div>
  <div v-if="isPreviewUploadList" class="demo-upload-list" v-for="(item,key) in uploadList" :key="key">
    <template v-if="item.status === 'finished'">
      <a :href="item.url">{{'附件'+(key+1)}}</a>
      <div class="demo-upload-list-cover">
        <Icon type="ios-trash-outline" @click.native="handleRemove(item)"></Icon>
      </div>
    </template>
    <template v-else>
      <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
    </template>
  </div>
  <Upload ref="upload" :data="uploadConfig.data" :show-upload-list="false" :default-file-list="uploadConfig.default_list" :on-success="handleSuccess" :on-error="handleError" :headers="uploadConfig.headers" :format="uploadConfig.format" :max-size="uploadConfig.max_size"
    :on-format-error="handleFormatError" :on-exceeded-size="handleMaxSize" :before-upload="handleBeforeUpload" :multiple="uploadConfig.multiple" :name="uploadConfig.file_name" type="drag" :action="uploadConfig.upload_url" style="display: inline-block;">
    <Button icon="ios-cloud-upload-outline" :loading="loading">{{ uploadConfig.button_text}} </Button>
  </Upload>
  <Divider orientation="left" v-if="isPreviewUploadList">已上传文件</Divider>
  <div class="galley-image-list" v-if="isPreviewUploadList">
    <ul class="pictures  row l-hide" ref="galley">
      <li v-for="(item,key) in formatFileList" :key="key"><a v-if="item.url" :href="item.url" target="_blank">附件{{ key +1}}</a></li>
    </ul>
  </div>

</div>
</template>
<script>
// import {
// deleteAttachment
// } from '@/api/common'

import Viewer from 'viewerjs'
import 'viewerjs/dist/viewer.css'

export default {
  props: {
    isDelete: {
      type: Boolean,
      default: false
    },
    isPreviewUploadList: true,
    uploadConfig: {
      type: Object,
      default: {
        headers: {
          'Authorization': window.access_token
        },
        format: ['txt', 'video', 'doc', 'sql', 'xlsx', 'docx'],
        max_size: 800, // 800KB
        upload_url: '',
        file_name: 'file',
        multiple: false,
        file_num: 0,
        data: [],
        default_list: [{
          name: '',
          attachment_id: 0,
          url: ''
        },
        {
          name: '',
          attachment_id: 0,
          url: ''
        }
        ]

      }
    }
  },
  data () {
    return {
      imgName: '',
      visible: false,
      uploadList: [],
      formatFileList: [],
      loading: false
    }
  },
  methods: {
    handleRemove (file) {
      const fileList = this.$refs.upload.fileList

      // if (file.attachment_id > 0 && (this.isDelete === true)) {
      //   deleteAttachment(file.attachment_id).then(res => {
      //     this.$Notice.success({
      //       title: '操作成功',
      //       desc: '文件删除成功'
      //     })
      //   })
      // }

      this.$refs.upload.fileList.splice(fileList.indexOf(file), 1)

      let formatFileList = this.fomatFile()
      this.$emit('input', formatFileList)
      this.$emit('on-upload-change', this.uploadList, formatFileList)
      this.ViewImage()
    },
    handleSuccess (res, file) {
      this.loading = false
      if (!res.hasOwnProperty('status') || (res.status !== 'success')) {
        this.$Notice.error({
          title: '出错了，请删除后重新上传',
          desc: res.message
        })
        return false
      }

      file.url = res.data.url
      file.name = res.data.original_name
      file.attachment_id = res.data.attachment_id

      let formatFileList = this.fomatFile()
      this.$emit('input', formatFileList)
      this.$emit('on-upload-change', this.uploadList, formatFileList)
      this.loading = false
      this.ViewImage()
    },
    handleError (error, file) {
      this.loading = false
      this.$Notice.error({
        title: '出错了',
        desc: error
      })
    },

    fomatFile () {
      let formatFileList = []
      this.uploadList.forEach(function (value, index, array) {
        formatFileList.push({
          attachment_id: value.attachment_id,
          url: value.url
        })
      })
      this.formatFileList = formatFileList

      if (this.uploadConfig.file_num === 1) {
        formatFileList = formatFileList[0]
      }
      return formatFileList
    },
    handleFormatError (file) {
      this.$Notice.warning({
        title: '文件格式不正确',
        desc: '文件 ' + file.name + ' 格式不正确。'
      })

      this.loading = false
      this.ViewImage()
    },
    handleMaxSize (file) {
      this.$Notice.warning({
        title: '超出文件大小限制',
        desc: '文件 ' + file.name + ' 太大，不能超过 ' + this.uploadConfig.max_size + 'kb'
      })

      this.loading = false
      this.ViewImage()
    },
    handleBeforeUpload () {
      const check = this.uploadList.length < this.uploadConfig.file_num

      if (!check) {
        this.$Notice.warning({
          title: '数量限制',
          desc: '最多只能上传' + this.uploadConfig.file_num + '个文件'
        })
        this.ViewImage()
      } else {
        this.loading = true
      }

      return check
    },
    ViewImage () {
      this.$nextTick(() => {
        $(function () {
          $('.l-hide').click(function () {
            $('.l-show').removeAttr('id').addClass('l-hide').removeClass('l-show')
            $(this).attr('id', 'galley')
            $(this).addClass('l-show')
            $(this).removeClass('l-hide')
            var galley = document.getElementById('galley')
            var viewer = new Viewer(galley, {
              url: 'data-original',
              toolbar: {
                oneToOne: true,
                prev: function () {
                  viewer.prev(true)
                },
                play: true,
                next: function () {
                  viewer.next(true)
                },
                update: function () {

                },
                download: function () {
                  const a = document.createElement('a')

                  a.href = viewer.image.src
                  a.download = viewer.image.alt
                  document.body.appendChild(a)
                  a.click()
                  document.body.removeChild(a)
                }
              }
            })
          })
        })
      })
    }
  },
  mounted () {
    this.uploadList = this.$refs.upload.fileList

    let formatFileList = this.fomatFile()
    if (formatFileList !== 'undefined') {
      this.$emit('input', formatFileList)
      this.$emit('on-upload-change', this.uploadList, formatFileList)
    }
    this.ViewImage()
  }
}
</script>
