<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="70" class-name="article-modal">
    <p slot="header">修改</p>
    <Form ref="formData" :model="formData" :rules="rules" label-position="left" :label-width="100">
      <FormItem label="分类">
        <Select v-model="formData.agree_type" filterable placeholder="请选择协议分类">
          <Option v-for="(item,key) in agreeTypeList" :value="key" :key="key">{{ item }} </Option>
        </Select>
      </FormItem>
      <FormItem label="名称" prop="name">
        <Input v-model="formData.name"></Input>
      </FormItem>
      <FormItem label="描述" prop="description">
        <Input type="textarea" v-model="formData.description" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="协议内容">
        <wang-editor v-if="formdataFinished" :cache="false" :value="formData.content" v-model="formData.content" @on-change="editContentChange" :upload-config='wangUploadConfig'></wang-editor>
      </FormItem>
    </Form>
    <div slot="footer">
      <Button type="text" @click="cancel">取消</Button>
      <Button type="primary" @click="editExcute" :loading='saveLoading'>保存
      </Button>
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
} from '@/api/user_agreement'
import WangEditor from '_c/common/wang-editor'

import InputHelper from '_c/common/input-helper'

export default {
  props: ['modalId', 'agreeTypeList'],
  components: {
    WangEditor,
    InputHelper
  },
  data () {
    return {
      modalShow: true,
      saveLoading: false,
      spinLoading: true,
      formData: {
        agree_type: '',
        name: '',
        description: '',
        content: ''
      },
      formdataFinished: false,
      wangUploadConfig: {
        headers: {
          'Authorization': window.access_token
        },
        wang_size: 1 * 1024 * 1024, // 1M
        uploadUrl: window.uploadUrl.uploadToLocaleUrl + '/pic/editor_agreement_content',
        params: {},
        max_length: 3,
        file_name: 'file',
        z_index: 10000,
        heightStyle: 'wang-editor-text-300'
      },
      rules: {
        title: [{
          required: true,
          message: '请填写协议名称',
          trigger: 'blur'
        },
        {
          type: 'string',
          min: 2,
          message: '协议名称至少要 2 个字符',
          trigger: 'blur'
        }
        ],
        content: [{
          required: true,
          message: '请填写协议内容',
          trigger: 'blur'
        }]
      }
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
          agree_type: res_data.agree_type,
          name: res_data.name,
          description: res_data.description,
          content: res_data.content.html
        }
        t.formdataFinished = true
        t.spinLoading = false
      })
    },
    editExcute () {
      let t = this
      t.$refs.formData.validate((valid) => {
        if (valid) {
          t.saveLoading = true
          edit(t.formData, t.modalId).then(res => {
            t.saveLoading = false
            t.modalShow = false
            t.$emit('on-edit-modal-success')
            this.$emit('on-edit-modal-hide')
            t.$Notice.success({
              title: res.message
            })
          }, function (error) {
            t.saveLoading = false
          })
        } else {
          t.saveLoading = false
        }
      })
    },
    cancel () {
      this.modalShow = false
      this.$emit('on-edit-modal-hide')
    },
    editContentChange (html, text) {
      // console.log(this.formData.content)
    }
  }
}
</script>
