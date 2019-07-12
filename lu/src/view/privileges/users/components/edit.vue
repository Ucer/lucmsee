<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="600">
    <p slot="header">修改</p>
    <Form ref="formData" :model="formData" :rules="rules" label-position="left" :label-width="100">
      <FormItem label="头像：">
        <upload v-if='formdataFinished' v-model="formData.avatar" :upload-config="imguploadConfig" @on-upload-change='uploadChange'></upload>
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
import Upload from '_c/common/upload'
import {
  edit,
  getInfoById
} from '@/api/user'

export default {
  components: {
    Upload
  },
  props: {
    modalId: {
      type: Number,
      default: 0
    },
    'tableStatus_is_admin': ''
  },
  data () {
    return {
      modalShow: true,
      saveLoading: false,
      spinLoading: true,
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
      formdataFinished: false,
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
        ]
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
          real_name: res_data.real_name,
          nickname: res_data.nickname,
          email: res_data.email,
          is_admin: res_data.is_admin,
          password: '',
          password_confirmation: '',
          avatar: {
            attachment_id: 0,
            url: res_data.avatar
          }
        }
        t.imguploadConfig.default_list = [t.formData.avatar]
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
    },
    uploadChange (fileList, formatFileList) {}
  }
}
</script>
