<template>
<div>
  <Drawer :closable="true" v-model="show" @on-close='closed' title="用户协议：" :width="platformIsPc?30:80">
    <p class="drawer-title">{{ info.name }}</p>
    <p v-html="info.content.html"></p>
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
      show: true
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
