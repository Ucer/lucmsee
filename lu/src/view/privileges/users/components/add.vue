<template>
  <div>
    <Modal v-model="modalShow" :closable='false' :mask-closable=false width="600">
      <p slot="header">{{ $t('add') }}</p>
      <Form ref="formData" :model="formData" :rules="rules" label-position="left" :label-width="100">
        <FormItem label="头像：">
          <upload v-model="formData.avatar" :upload-config="imguploadConfig" @on-upload-change='uploadChange'></upload>
        </FormItem>
        <FormItem label="真实姓名：" prop="real_name">
          <Input v-model="formData.real_name"></Input>
        </FormItem>
        <FormItem label="昵称：" prop="nickname">
          <Input v-model="formData.nickname"></Input>
        </FormItem>
        <FormItem label="邮箱：">
          <Input v-model="formData.email"></Input>
        </FormItem>
        <FormItem label="登录密码：" prop="password">
          <Input type="password" v-model="formData.password"></Input>
        </FormItem>
        <FormItem label="登录密码确认：" prop="password_confirmation">
          <Input type="password" v-model="formData.password_confirmation"></Input>
        </FormItem>
        <FormItem label="可登录后台：">
          <RadioGroup v-model="formData.is_admin">
            <Radio v-for="(item,key) in tableStatus_is_admin" :key="key" :label="key">{{ item }}</Radio>
          </RadioGroup>
        </FormItem>
      </Form>
      <div slot="footer">
        <Button type="text" @click="cancel">{{ $t('cancel') }}</Button>
        <Button type="primary" @click="addExcute" :loading='saveLoading'>{{ $t('save') }}</Button>
      </div>
    </Modal>
  </div>
</template>
<script>
import {
  add
} from '@/api/user'

import Upload from '_c/common/upload'

export default {
  props: ['tableStatus_is_admin'],
  components: {
    Upload
  },
  data () {
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
        }
      },
      imguploadConfig: {
        headers: {
          'Authorization': window.access_token
        },
        format: ['jpg', 'jpeg', 'png', 'gif'],
        max_size: 500,
        upload_url: window.uploadUrl.uploadToFileSystemUrl + '/pic/avatar',
        file_name: 'file',
        multiple: false,
        file_num: 1,
        data: {
          user_id: this.$store.state.user.user_id,
          max_width: 300,
          plat_name: window.systemConfigIndexFile.platName,
          access_token: window.systemConfigIndexFile.domainForFileSystem.access_token
        },
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
        }
        ],
        password: [{
          validator: validatePassword,
          trigger: 'blur'
        }],
        password_confirmation: [{
          validator: validatePasswordConfirm,
          trigger: 'blur'
        }]
      }
    }
  },
  methods: {
    addExcute () {
      let t = this
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
            t.saveLoading = false
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
      this.$emit('on-add-modal-hide')
    },
    editContentChange (html, text) {
      // console.log(this.formData.content)
    },
    uploadChange (fileList, formatFileList) {
    }
  }
}
</script>
