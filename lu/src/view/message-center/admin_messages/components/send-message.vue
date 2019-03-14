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
      <FormItem label="消息接收人：">
        <Select v-model="formData.users" multiple filterable remote :remote-method="searchAdminUserPassEmailExcute" :loading="searchLoading" placeholder="请输入管理员邮箱搜索">
          <Option v-for="(item, key) in userList" :value="item.id" :key="item.id">{{item.email}}({{item.mobile}})</Option>
        </Select>
        <input-helper text="不选将发送给所有人" :style-class="'input-helper-error'"></input-helper>
      </FormItem>
    </Form>
    <div slot="footer">
      <Button type="text" @click="cancel">{{ $t('cancel') }}</Button>
      <Button type="primary" @click="sendMessageToAdminExcute" :loading='saveLoading'>{{ $t('save') }} </Button>
    </div>
  </Modal>
</div>
</template>
<script>
import {
  sendMessageToAdmin
} from '@/api/admin_message'
import {
  searchAdminUserPassEmail
} from '@/api/user'
import InputHelper from '_c/common/input-helper'

export default {
  props: ['tableStatus_message_type'],
  components: {
    InputHelper
  },
  data() {
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
        }],
      },
    }
  },
  methods: {
    searchAdminUserPassEmailExcute: function(input) {
      let t = this;
      if (input.length < 3) {
        return false
      }
      t.searchLoading = true
      searchAdminUserPassEmail(input).then(res => {
        this.userList = res.data
        t.searchLoading = false
      })
    },
    sendMessageToAdminExcute() {
      let t = this
      t.$refs.formData.validate((valid) => {
        if (valid) {
          t.saveLoading = true
          sendMessageToAdmin(t.formData).then(res => {
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
    cancel() {
      this.modalShow = false
      this.$emit('on-add-modal-hide')
    },
  }
}
</script>
