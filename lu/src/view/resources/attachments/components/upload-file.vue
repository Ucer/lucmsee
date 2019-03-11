<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="600">
    <p slot="header">上传文件</p>
    <Form ref="formData" :model="formData" label-position="left" :label-width="100">
      <FormItem label="文件">
        <upload-file v-model="formData.video" :upload-config="fileuploadConfig" :is-preview-upload-list=true @on-upload-change='uploadfileChange'></upload-file>
      </FormItem>
    </Form>
    <div slot="footer">
      <Button type="text" @click="cancel"> 返回</Button>
    </div>
  </Modal>
</div>
</template>
<script>
import UploadFile from '_c/common/upload-file'

export default {
  components: {
    UploadFile
  },
  data() {
    return {
      modalShow: true,
      saveLoading: false,
      formData: {
        video: {
          attachment_id: 0,
          url: ''
        },
      },
      fileuploadConfig: {
        headers: {
          'Authorization': window.access_token
        },
        format: ['xlsx', 'doc', 'docx', 'txt', 'sql','exe'],
        max_size: 1024 * 1000, // 800KB
        upload_url: window.uploadUrl.uploadToLocaleUrl + '/file/lucmsee',
        file_name: 'file',
        multiple: false,
        file_num: 1,
        data: {},
        button_text: '上传文件',
        default_list: []

      },
    }
  },
  methods: {
    cancel() {
      this.modalShow = false
      this.$emit('on-upload-file-modal-hide')
    },
    uploadfileChange(fileList, formatFileList) {}
  }
}
</script>
