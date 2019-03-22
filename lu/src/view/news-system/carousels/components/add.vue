<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="600">
    <p slot="header">{{ $t('add') }}</p>
    <Form ref="formData" :model="formData" :rules="rules" label-position="left" :label-width="100">
      <FormItem label="标题">
        <Input v-model="formData.title" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="图片：">
        <upload v-model="formData.cover_image" :upload-config="imguploadConfig" @on-upload-change='uploadChange'></upload>
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
      <Button type="primary" @click="addExcute" :loading='saveLoading'>{{ $t('save') }} </Button>
    </div>
  </Modal>
</div>
</template>
<script>
import {
  add
} from '@/api/carousel'
import Upload from '_c/common/upload'

export default {
  components: {
    Upload
  },
  data() {
    return {
      modalShow: true,
      saveLoading: false,
      formData: {
        title: '',
        cover_image: {
          attachment_id: 0,
          url: ''
        },
        description: '',
        weight: 50,
        url:''
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
      rules: {},
    }
  },
  methods: {
    addExcute() {
      let t = this
      t.saveLoading = true
      add(t.formData).then(res => {
        t.saveLoading = false
        t.modalShow = false
        t.$emit('on-add-modal-success')
        t.$emit('on-add-modal-hide')
        t.$Notice.success({
          title: res.message
        })
      }, function(error) {
        t.saveLoading = false;
      })
    },
    cancel() {
      this.modalShow = false
      this.$emit('on-add-modal-hide')
    },
    uploadChange(fileList, formatFileList) {}
  }
}
</script>
