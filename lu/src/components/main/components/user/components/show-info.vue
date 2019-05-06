<template>
<div>
  <Drawer :closable="true" v-model="show" @on-close='closed' title="个人资料" :width="platformIsPc?30:80">
    <div class="drawer-profile-user">
      <Row>
        <p slot="title" class="text-center">管理员信息</p>
        <Form label-position="right" :label-width="100">
          <FormItem label="头像：">
            <Avatar v-if="info.avatar" :src="info.avatar" />
            <Avatar size="large" v-else style="color: #f56a00;background-color: #fde3cf">{{ info.real_name.substr(0,1)}}</Avatar>
          </FormItem>
          <FormItem label="昵称：">
            <span>{{ info.nickname}}</span>
          </FormItem>
          <FormItem label="账号：">
            <span class="blod-font">{{ info.email}}</span>
          </FormItem>
          <FormItem label="角色：">
            <Tag type="border" color="success" v-for="(item,key) in info.roles" :key="key">{{ item}}</Tag>
          </FormItem>
          <FormItem label="手机号：">
            <span>{{ info.mobile}}</span>
          </FormItem>
          <FormItem label="真实姓名：">
            <span>{{ info.real_name}}</span>
          </FormItem>
        </Form>
      </Row>
    </div>
  </Drawer>
</div>
</template>
<script>
export default {
  props: {
    info: {
      default: ''
    }
  },
  data () {
    return {
      show: true,
      agreement: '',
      spinLoading: true
    }
  },
  created () {},
  computed: {
    platformIsPc: function () {
      return this.globalPlatformType() === 'pc'
    }
  },
  methods: {
    closed () {
      this.show = false
      this.$emit('show-modal-close')
    }
  }
}
</script>
