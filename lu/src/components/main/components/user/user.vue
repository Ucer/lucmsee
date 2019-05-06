<template>
<div class="user-avator-dropdown">
  <Dropdown @on-click="handleClick">
    <Avatar v-if="stateAvatar" :src="stateAvatar" />
    <Avatar size="large" v-else style="color: #f56a00;background-color: #fde3cf">{{ stateRealName.substr(0,1)}}</Avatar>
    <span>{{ stateNickname }}</span>
    <Icon :size="18" type="md-arrow-dropdown"></Icon>
    <DropdownMenu slot="list">
      <DropdownItem name="profile">个人资料</DropdownItem>
      <DropdownItem name="editPassword">修改密码</DropdownItem>
      <DropdownItem name="logout">退出登录</DropdownItem>
    </DropdownMenu>
  </Dropdown>
  <show-info v-if='showInfoModal.show' :info='showInfoModal.info' @show-modal-close="showModalClose"></show-info>

  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="500">
    <p slot="header">登录密码修改</p>
    <Form ref="editPasswordFormData" :model="formData" :rules="rules" label-position="left" :label-width="100">
      <FormItem label="新密码：" prop="password">
        <Input type="password" v-model="formData.password"></Input>
      </FormItem>
      <FormItem label="密码确认：" prop="password_confirmation">
        <Input type="password" v-model="formData.password_confirmation"></Input>
      </FormItem>
    </Form>
    <div slot="footer">
      <Button type="text" @click="cancel">取消</Button>
      <Button type="primary" @click="resetPasswordExcute" :loading='saveLoading'>修改 </Button>
    </div>
  </Modal>
</div>
</template>

<script>
import './user.less'
import ShowInfo from './components/show-info'
import {
  getUserInfo
  ,
  resetPassword
} from '@/api/user'

import {
  mapActions
} from 'vuex'
export default {
  components: {
    ShowInfo
  },
  data () {
    const validatePassword = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入登录密码'))
      } else {
        if (this.formData.password !== '') {
          // 对第二个密码框单独验证
          this.$refs.editPasswordFormData.validateField('password_confirmation')
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
      showInfoModal: {
        show: false,
        info: ''
      },
      modalShow: false,
      formData: {
        password: '',
        password_confirmation: ''
      },
      saveLoading: false,
      rules: {
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
  computed: {
    stateAvatar () {
      return this.$store.state.user.avator
    },
    stateNickname () {
      return this.$store.state.user.nickname
    },
    stateRealName () {
      return this.$store.state.user.real_name
    },
    stateEmail () {
      return this.$store.state.user.email
    }
  },
  methods: {
    ...mapActions([
      'handleLogOut'
    ]),
    handleClick (name) {
      switch (name) {
        case 'logout':
          this.handleLogOut().then(() => {
            this.$router.push({
              name: 'login'
            })
          })
          break
        case 'profile':
          this.getUserInfoExcute()
          break
        case 'editPassword':
          this.modalShow = true
          break
      }
    },
    showModalClose () {
      this.showInfoModal.show = false
    },
    getUserInfoExcute () {
      let t = this
      getUserInfo().then(res => {
        let res_data = res.data
        t.showInfoModal.info = res_data
        t.showInfoModal.show = true
      })
    },
    resetPasswordExcute () {
      let t = this
      t.$refs.editPasswordFormData.validate((valid) => {
        if (valid) {
          t.saveLoading = true
          resetPassword(t.formData).then(res => {
            t.saveLoading = false
            t.modalShow = false
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
      this.formData = {
        password: '',
        password_confirmation: ''
      }
    }
  }
}
</script>
