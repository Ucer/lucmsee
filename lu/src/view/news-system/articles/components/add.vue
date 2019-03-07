<style lang="less">
.article-modal {
    .ivu-modal-body {
        overflow: scroll;
        overflow-x: hidden;
        max-height: 700px;
    }
}
</style>
<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="100" class-name="article-modal">
    <p slot="header">{{ $t('add') }}</p>

    <Row>
      <Col span="16">
      <Form ref="formData" :model="formData" :rules="rules" label-position="left" :label-width="100">
        <FormItem label="分类：">
          <Select v-model="formData.category_id" filterable placeholder="请选择文章分类">
            <Option v-for="(item,key) in articleCategories" :value="item.id" :key="key">{{ item.name }} </Option>
          </Select>
        </FormItem>
        <FormItem label="标题：" prop="title">
          <Input v-model="formData.title"></Input>
        </FormItem>
        <FormItem label="封面：">
          <upload v-model="formData.cover_image" :upload-config="imguploadConfig" @on-upload-change='uploadChange'></upload>
        </FormItem>
        <FormItem label="关键词：" prop="keywords">
          <Input type="textarea" v-model="formData.keywords" placeholder="以英文逗号隔开"></Input>
        </FormItem>
        <FormItem label="描述：" prop="description">
          <Input type="textarea" v-model="formData.descriptions" placeholder="请输入"></Input>
        </FormItem>
        <FormItem label="文章内容：">
          <wang-editor v-model="formData.content" @on-change="editContentChange" :upload-config='wangUploadConfig'></wang-editor>
        </FormItem>
      </Form>
      </Col>

      <Col span="8" class="padding-left-20">
      <Card>
        <p slot="title">
          <Icon type="paper-airplane"></Icon>
          其它信息
        </p>
        <Form label-position="right" :label-width="80">
          <FormItem label="启用状态：">
            <RadioGroup v-model="formData.enable">
              <Radio v-for="(item,key) in tableStatus_enable" :label="key">{{ item }}</Radio>
            </RadioGroup>
          </FormItem>
          <FormItem label="是否置顶：">
            <RadioGroup v-model="formData.top">
              <Radio v-for="(item,key) in tableStatus_top" :label="key">{{ item }}</Radio>
            </RadioGroup>
          </FormItem>
          <FormItem label="是否推荐：">
            <RadioGroup v-model="formData.recommend">
              <Radio v-for="(item,key) in tableStatus_recommend" :label="key">{{ item }}</Radio>
            </RadioGroup>
          </FormItem>
        </Form>
      </Card>
      </Col>
    </Row>
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
} from '@/api/article'

import Upload from '_c/common/upload'

import WangEditor from '_c/common/wang-editor'

export default {
  props: ['articleCategories','tableStatus_enable', 'tableStatus_recommend', 'tableStatus_top'],
  components: {
    Upload,
    WangEditor
  },
  data() {
    const validatePassword = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入登录密码'))
      } else {
        if (this.formData.password !== '') {
          // 对第二个密码框单独验证
          this.$refs.formData.validateField('password_confirmation')
        }
        callback()
      }
    }
    const validatePasswordConfirm = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入确认密码'))
      } else if (value !== this.formData.password) {
        callback(new Error('两次密码不一致 '))
      } else {
        callback()
      }
    }
    return {
      modalShow: true,
      saveLoading: false,
      formData: {
        title: '',
        cover_image: {
          attachment_id: 0,
          url: '',
        },
        enable: 'F',
        keywords: '',
        descriptions: '',
        content: '',
        category_id: 0,
        weight: 20,
        top: 'F',
        recommend: 'F',
        access_type: 'PUB',
        access_value: '',
        tags: 0,
      },
      imguploadConfig: {
        headers: {
          'Authorization': window.access_token
        },
        format: ['jpg', 'jpeg', 'png', 'gif'],
        max_size: 500,
        upload_url: window.uploadUrl.uploadToLocaleUrl + '/pic/avatar',
        file_name: 'file',
        multiple: false,
        file_num: 1,
        data: {},
        default_list: []
      },
      wangUploadConfig: {
        headers: {
          'Authorization': window.access_token
        },
        wang_size: 1 * 1024 * 1024, // 1M
        uploadUrl: window.uploadUrl.uploadToLocaleUrl + '/pic/editor_article_content',
        params: {},
        max_length: 3,
        file_name: 'file',
        z_index: 10000,
        heightStyle: 'wang-editor-text-300'
      },
      rules: {
        title: [{
            required: true,
            message: '请填写文章标题',
            trigger: 'blur'
          },
          {
            type: 'string',
            min: 2,
            message: '文章标题至少要 2 个字符',
            trigger: 'blur'
          }
        ],
        article_category_id: [{
          required: true,
          message: '请选择分类',
          trigger: 'blur'
        }],
        content: [{
          required: true,
          message: '请填写文章内容',
          trigger: 'blur'
        }]
      },
    }
  },
  methods: {
    addExcute() {
      let t = this;
      t.$refs.formData.validate((valid) => {
        if (valid) {
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
        } else {
          t.saveLoading = false
        }
      })
    },
    cancel() {
      this.modalShow = false
      this.$emit('on-add-modal-hide')
    },
    editContentChange(html, text) {
      // console.log(this.formData.content)
    },
    uploadChange(fileList, formatFileList) {}
  }
}
</script>
