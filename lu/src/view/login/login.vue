<style lang="less">
  @import './login.less';
</style>

<template>
  <div class="login" id="loginPage">
    <div class="login-con">
      <Card icon="log-in" title="欢迎登录" :bordered="false">
        <div class="form-con">
          <Form ref="formData" :model="formData" :rules="rules" @keydown.enter.native="handleSubmit">
            <FormItem prop="email">
              <Input prefix="ios-person" v-model="formData.email" placeholder="请输入用户名"/>
            </FormItem>
            <FormItem prop="password">
              <Input prefix="md-lock" type="password" v-model="formData.password" placeholder="请输入密码"/>
            </FormItem>
            <FormItem prop="captcha" class="captcha-img">
              <Input v-model="formData.captcha" placeholder="请输入验证码">
                <img style="padding:0" @click="getCaptchaExcute()" slot="append" :src="captcha_url" alt=""/>
              </Input>
            </FormItem>
            <FormItem>
              <Button type="primary" :loading="saveLoading" @click="handleSubmit">
                <span v-if="!saveLoading">登录</span>
                <span v-else>正在登录...</span>
              </Button>
            </FormItem>
          </Form>
          <p class="login-tip">Lucms EE © 2019 Powered by Ucer ❤</p>
        </div>
      </Card>
    </div>
  </div>
</template>

<script>
import {
  getCaptcha
} from '@/api/common'
import {
  mapActions
} from 'vuex'
import particles from 'particles.js'

export default {
  data () {
    return {
      saveLoading: false,
      captcha_url: '',
      formData: {
        email: '',
        password: '',
        captcha: '',
        captcha_key: ''
      },
      rules: {
        email: [{
          required: true,
          message: '账号不能为空',
          trigger: 'blur'
        }],
        password: [{
          required: true,
          message: '密码不能为空',
          trigger: 'blur'
        }],
        captcha: [{
          required: true,
          message: '验证码不能为空',
          trigger: 'blur'
        }]
      }
    }
  },
  created () {
    this.getCaptchaExcute()
  },
  mounted: function () {
    // 此处不能写代理地址，否则会报 vue 错误
    particlesJS.load('loginPage', window.baseUrl + '/storage/common/particles.json')
  },
  methods: {
    ...mapActions([
      'handleLogin',
      'getUserInfoExcute'
    ]),
    handleSubmit () {
      let t = this
      t.$refs.formData.validate((valid) => {
        if (valid) {
          t.saveLoading = true
          this.handleLogin({
            email: t.formData.email,
            password: t.formData.password,
            captcha: t.formData.captcha,
            captcha_key: t.formData.captcha_key
          }).then(res => {
            if (res.status === 'success') {
              t.getUserInfoExcute().then(res => {
                t.$router.push({
                  name: 'home'
                })
              })
            } else {
              t.$Notice.error({
                title: '出错了',
                desc: res.message
              })
            }
          }).catch((err) => {
            t.saveLoading = false
          })
        }
      })
    },
    getCaptchaExcute () {
      let t = this
      getCaptcha().then(res => {
        const response_data = res.data
        t.captcha_url = response_data.img
        t.formData.captcha_key = response_data.key
      }, function (error) {
      })
    }
  }
}
</script>
