<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="600">
    <p slot="header">{{ $t('add') }}</p>
    <Form ref="formData" :model="formData" :rules="rules" label-position="left" :label-width="100">
      <FormItem label="标题：" prop="title">
        <Input v-model="formData.title" placeholder="请输消息标题"></Input>
      </FormItem>
      <FormItem label="消息内容：" prop="content">
        <Input type="textarea" v-model="formData.content" placeholder="请输入消息内容"></Input>
      </FormItem>
      <FormItem label="消息类型：">
        <Select v-model="formData.message_type" filterable placeholder="请选择消息类型">
          <Option v-for="(item,key) in tableStatus_message_type" :value="key" :key="key">{{ item }} </Option>
        </Select>
      </FormItem>
      <FormItem label="是否在首页提示：">
        <RadioGroup v-model="formData.is_alert_at_home">
          <Radio v-for="(item,key) in tableStatus_is_alert_at_home" :label="key" :key="key">{{ item }}</Radio>
        </RadioGroup>
      </FormItem>
      <FormItem label="消息接收人：">
        <Select v-model="formData.users" multiple filterable remote :remote-method="searchUserPassMobileExcute" :loading="searchLoading" placeholder="请输入手机号搜索">
          <Option v-for="(item, key) in userList"  :value="item.id" :key="key">{{item.real_name}}({{item.mobile}})</Option>
        </Select>
        <input-helper text="不选将发送给所有人" :style-class="'input-helper-error'"></input-helper>
      </FormItem>
      <FormItem label="跳转 url：">
        <Input v-model="formData.url" placeholder="跳转 url"></Input>
      </FormItem>
    </Form>
    <div slot="footer">
      <Button type="text" @click="cancel">{{ $t('cancel') }}</Button>
      <Button type="primary" @click="sendMessageToAppUserExcute" :loading='saveLoading'>{{ $t('save') }} </Button>
    </div>
  </Modal>
</div>
</template>
<script>
import {
  sendMessageToAppUser
} from '@/api/app_message'
import {
  searchUserPassMobile
} from '@/api/user'
import InputHelper from '_c/common/input-helper'

export default {
  props: ['tableStatus_message_type', 'tableStatus_is_alert_at_home'],
  components: {
    InputHelper
  },
  data () {
    return {
      modalShow: true,
      saveLoading: false,
      searchLoading: false,
      userList: [],
      formData: {
        title: '',
        message_type: '',
        url: '',
        users: '',
        content: '',
        is_alert_at_home: 'F'
      },
      rules: {
        title: [{
          required: true,
          message: '请填写消息标题',
          trigger: 'blur'
        }],
        content: [{
          required: true,
          message: '请填写消息内容',
          trigger: 'blur'
        }],
        type: [{
          required: true,
          message: '请选择消息类型',
          trigger: 'blur'
        }]
      }
    }
  },
  methods: {
    searchUserPassMobileExcute: function (input) {
      let t = this
      if (input.length < 3) {
        return false
      }
      t.searchLoading = true
      searchUserPassMobile(input).then(res => {
        this.userList = res.data
        t.searchLoading = false
      })
    },
    sendMessageToAppUserExcute () {
      let t = this
      t.$refs.formData.validate((valid) => {
        if (valid) {
          t.saveLoading = true
          sendMessageToAppUser(t.formData).then(res => {
            t.saveLoading = false
            t.modalShow = false
            t.$emit('on-add-modal-success')
            t.$emit('on-add-modal-hide')
            t.$Notice.success({
              title: res.message
            })
          })
        }
      })
    },
    cancel () {
      this.modalShow = false
      this.$emit('on-add-modal-hide')
    }
  }
}
</script>
