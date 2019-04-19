<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="600">
    <p slot="header">{{ $t('add') }}</p>
    <Form ref="formData" :model="formData" :rules="rules" label-position="left" :label-width="100">
      <FormItem label="app：">
        <Select v-model="formData.name" filterable placeholder="请选择app">
          <Option v-for="(item,key) in tableStatus.name" :value="key" :key="key">{{ item }} </Option>
        </Select>
      </FormItem>
      <FormItem label="系统：">
        <Select v-model="formData.mobile_os"  placeholder="请选择系统">
          <Option v-for="(item,key) in tableStatus.mobile_os" :value="key" :key="key">{{ item }} </Option>
        </Select>
      </FormItem>
      <FormItem label="安装包：">
        <upload-file :isPreviewUploadList="true" v-model="formData.package_url" :upload-config="fileuploadConfig"  @on-upload-change='uploadfileChange'></upload-file>
      </FormItem>
      <FormItem label="强制更新：">
        <RadioGroup v-model="formData.is_force_update">
          <Radio v-for="(item,key) in tableStatus.is_force_update" :key="key" :label="key">{{ item }}</Radio>
        </RadioGroup>
      </FormItem>
      <FormItem label="版本号：">
        <Input v-model="formData.version_sn" placeholder="请输入版本号" style="width: auto"></Input>
      </FormItem>
      <FormItem label="简介：">
        <Input type="textarea" v-model="formData.description" :rows="4" placeholder="请输入"></Input>
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
} from '@/api/app_version'
import UploadFile from '_c/common/upload-file'

export default {
  components: {
    UploadFile
  },
  props: ['tableStatus'],
  data () {
    return {
      modalShow: true,
      saveLoading: false,
      formData: {
        name: '',
        mobile_os: '',
        version_sn: '',
        description: '',
        is_force_update: 'F',
        package_url: {
          attachment_id: 0,
          url: ''
        }
      },
      fileuploadConfig: {
        headers: {
          'Authorization': window.access_token
        },
        format: ['apk', 'wgt'],
        max_size: 1024 * 20000, // 800KB
        upload_url: window.uploadUrl.uploadToLocaleUrl + '/file/apk',
        file_name: 'file',
        multiple: false,
        file_num: 1,
        data: {},
        button_text: '上传文件',
        default_list: []

      },
      rules: {}
    }
  },
  methods: {
    addExcute () {
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
      }, function (error) {
        t.saveLoading = false
      })
    },
    cancel () {
      this.modalShow = false
      this.$emit('on-add-modal-hide')
    },
    uploadfileChange (fileList, formatFileList) {}
  }
}
</script>
