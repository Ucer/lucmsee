<template>
<div>
  <Drawer :closable="true" v-model="show" @on-close='closed' title="版本信息" :width="platformIsPc?30:80">
    <div class="drawer-profile">
      <Row>
        <li> <span class="blod-font">版本号：</span> {{info.version_sn}} </li>
        <li> <span class="blod-font">发布时间：</span> {{info.version_sn}} </li>
      </Row>
      <h2 class="text-center margin-top-8">版本描述</h2>
      <div class="text-center" v-html='info.description'> </div>
    </div>
  </Drawer>
</div>
</template>
<script>
export default {
  props: ['info'],
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
