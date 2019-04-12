<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="600">
    <p slot="header">{{ $t('edit') }}</p>
    <Form ref="formData" :model="formData" :rules="rules" label-position="left" :label-width="100">
      <FormItem label="标题">
        <Input v-model="formData.title" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="图片：">
        <upload v-if='formdataFinished' v-model="formData.cover_image" :upload-config="imguploadConfig" @on-upload-change='uploadChange'></upload>
      </FormItem>
      <FormItem label="跳转地址">
        <Input v-model="formData.url" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="描述">
        <Input type="textarea" v-model="formData.description" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="排序：">
        <Input v-model="formData.weight" placeholder="请输入序号" style="width: auto"></Input>
      </FormItem>
    </Form>
    <div slot="footer">
      <Button type="text" @click="cancel">{{ $t('cancel') }}</Button>
      <Button type="primary" @click="editExcute" :loading='saveLoading'>{{ $t('save') }} </Button>
    </div>
    <div class="demo-spin-container" v-if='spinLoading === true'>
      <Spin fix>
        <Icon type="load-c" size=18 class="spin-icon-load"></Icon>
        <div>{{ $t('table_loading') }}</div>
      </Spin>
    </div>
  </Modal>
</div>
</template>
<script>
import {
  edit,
  getInfoById
} from '@/api/carousel'
import Upload from '_c/common/upload'

export default {
  components: {
    Upload
  },
  props: {
    modalId: {
      type: Number,
      default: 0
    }
  },
  data () {
    return {
      modalShow: true,
      saveLoading: false,
      spinLoading: true,
      formdataFinished: false,
      formData: {
        title: '',
        cover_image: {
          attachment_id: 0,
          url: ''
        },
        description: '',
        weight: 50,
        url: ''
      },
      imguploadConfig: {
        headers: {
          'Authorization': window.access_token
        },
        format: ['jpg', 'jpeg', 'png', 'gif'],
        max_size: 500,
        upload_url: window.uploadUrl.uploadToLocaleUrl + '/pic/carousel',
        file_name: 'file',
        multiple: false,
        file_num: 1,
        data: {},
        default_list: []
      },
      rules: {}
    }
  },
  mounted () {
    if (this.modalId > 0) {
      this.getInfoByIdExcute()
    }
  },
  methods: {
    getInfoByIdExcute () {
      let t = this
      getInfoById(t.modalId).then(res => {
        let res_data = res.data
        t.formData = {
          id: res_data.id,
          title: res_data.title,
          url: res_data.url,
          weight: res_data.weight,
          description: res_data.description,
          cover_image: {
            attachment_id: 0,
            url: res_data.cover_image
          }
        }
        t.imguploadConfig.default_list = [t.formData.cover_image]
        t.spinLoading = false
        t.formdataFinished = true
      })
    },
    editExcute () {
      let t = this
      t.saveLoading = true
      edit(t.formData, t.formData.id).then(res => {
        t.saveLoading = false
        t.modalShow = false
        t.$emit('on-edit-modal-success')
        t.$emit('on-edit-modal-hide')
        t.$Notice.success({
          title: res.message
        })
      }, function (error) {
        t.saveLoading = false
      })
    },
    cancel () {
      this.modalShow = false
      this.$emit('on-edit-modal-hide')
    },
    uploadChange (fileList, formatFileList) {}
  }
}
</script>
