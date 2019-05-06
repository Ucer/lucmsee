<template>
<div>
  <Drawer :closable="true" v-model="show" @on-close='closed' title="消息详情" :width="platformIsPc?30:80">
    <div class="drawer-profile">
      <Row>
        <li> <span class="blod-font">消息标题：</span> {{info.title}} </li>
        <li v-if="info.admin_user"> <span class="blod-font">发送人：</span>{{ info.admin_user.real_name }} ({{ info.admin_user.mobile }}) </li>
        <li v-if="info.user"><span class="blod-font"> 接收人：</span>{{ info.user.real_name }} ({{ info.user.mobile }}) </li>
      </Row>
      <h2 class="text-center margin-top-8">消息内容</h2>
      <div class="text-center" v-html='info.content'> </div>
    </div>
  </Drawer>
</div>
</template>
<script>
export default {
  props: {
    info: {
      type: Object,
      default: ''
    }
  },
  data () {
    return {
      show: true,
      agreement: ''

    }
  },
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
