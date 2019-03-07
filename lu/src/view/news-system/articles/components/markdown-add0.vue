<style lang="less">
.article-modal {
    .ivu-modal-body {
        overflow: scroll;
        overflow-x: hidden;
        max-height: 800px;
    }
}
</style>
<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="800" class-name="article-modal" scrollable="true">
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
          <input-helper text="以英文逗号隔开"></input-helper>
        </FormItem>
        <FormItem label="描述：" prop="description">
          <Input type="textarea" v-model="formData.descriptions" placeholder="请输入"></Input>
        </FormItem>
        <FormItem label="文章内容：">
          <markdown-editor v-model="formData.content" :upload_url="markdownUploadUrl" :cache='true' />
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

export default {
  props: ['tableStatus_enable', 'tableStatus_recommend', 'tableStatus_top'],
  components: {
    Upload
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
        nickname: '',
        real_name: '',
        email: '',
        is_admin: 'F',
        password: '',
        password_confirmation: '',
        avatar: {
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
        upload_url: window.uploadUrl.uploadToLocaleUrl + '/pic/avatar',
        file_name: 'file',
        multiple: false,
        file_num: 1,
        data: {},
        default_list: []
      },
      rules: {
        real_name: [{
            required: true,
            message: '请填写真实姓名',
            trigger: 'blur'
          },
          {
            type: 'string',
            min: 2,
            message: '真实姓名至少要 2 个字符',
            trigger: 'blur'
          }
        ],
        email: [{
            required: true,
            message: '请填写邮箱',
            trigger: 'blur'
          },
          {
            type: 'email',
            message: '邮箱格式不正确',
            trigger: 'blur'
          },
        ],
        password: [{
          validator: validatePassword,
          trigger: 'blur'
        }],
        password_confirmation: [{
          validator: validatePasswordConfirm,
          trigger: 'blur'
        }],
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
