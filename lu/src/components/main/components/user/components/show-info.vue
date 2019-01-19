<template>
<div>
  <Drawer :closable="true" v-model="show" @on-close='closed' title="个人资料" :width="platformIsPc?30:80">
    <div class="drawer-profile-user">
      <Row>
        <p slot="title" class="text-center">管理员信息</p>
        <Form label-position="right" :label-width="100">
          <FormItem label="头像：">
            <Avatar :src="info.avatar" size="large" />
          </FormItem>
          <FormItem label="昵称：" prop="flag">
            <span>{{ info.name}}</span>
          </FormItem>
          <FormItem label="账号：" prop="flag">
            <span style="font-weight:700">{{ info.email}}</span>
          </FormItem>
          <FormItem label="角色：">
            <Tag type="border" color="success" v-for="(item,key) in info.roles">{{ item}}</Tag>
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
  data() {
    return {
      show: true,
      agreement: '',
      spinLoading: true
    }
  },
  created() {},
  computed: {
    platformIsPc: function() {
      return this.globalPlatformType() == 'pc' ? true : false
    }
  },
  methods: {
    closed() {
      this.show = false
      this.$emit('show-modal-close')
    }
  }
}
</script>
