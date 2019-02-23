
<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="600">
    <p slot="header">上传图片</p>
    <Form ref="formData" :model="formData"  label-position="left" :label-width="100">
      <FormItem label="头像：">
        <upload v-model="formData.image" :upload-config="imguploadConfig" @on-upload-change='uploadChange'></upload>
      </FormItem>
    </Form>
    <div slot="footer">
      <Button type="text" @click="cancel"> 返回</Button>
    </div>
  </Modal>
</div>
</template>
<script>
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
        image: {
          attachment_id: 0,
          url: ''
        },
      },
      imguploadConfig: {
        headers: {
          'Authorization': window.access_token
        },
        format: ['jpg', 'jpeg', 'png', 'gif'],
        max_size: 500,
        upload_url: window.uploadUrl.imageUploadToLocaleUrl + '/lucmsee',
        file_name: 'file',
        multiple: false,
        file_num: 1,
        data: {
          a: 1,
          b: 2
        },
        default_list: []
      },
    }
  },
  methods: {
    cancel() {
      this.modalShow = false
      this.$emit('on-upload-image-modal-hide')
    },
    editContentChange(html, text) {
      // console.log(this.formData.content)
    },
    uploadChange(fileList, formatFileList) {}
  }
}
</script>
