<template>
  <div>
    <h1>添加示例</h1>
<!--    <Button type="info" size="small" style="margin-right: 5px" @click="showMapModal()">地图选点</Button>-->
<!--    <ucer-map v-model="formData.amapData" v-if='mapModal.show'  @on-map-modal-hide="mapModalHide"></ucer-map>-->
<!--    <span>选择位置：lng {{formData.amapData.lng}}, lat {{formData.amapData.lat}},{{formData.amapData.address}}</span>-->

    <h1>修改示例</h1>
    <Button type="info" size="small" style="margin-right: 5px" @click="showMapModal()">地图选点</Button>
    <ucer-map :amapData="amapData" v-model="formData.amapData" v-if='mapModal.show'  @on-map-modal-hide="mapModalHide"></ucer-map>
    <span>选择位置：lng {{formData.amapData.lng}}, lat {{formData.amapData.lat}},{{formData.amapData.address}}</span>
  </div>
</template>
<script>
import UcerMap from '_c/common/ucer-map'

export default {
  components: {
    UcerMap
  },
  data () {
    return {
      formData: {
        amapData: {
          lng: 0,
          lat: 0,
          address: ''
        }
      },
      mapModal: {
        show: false
      }
    }
  },
  methods: {
    mapModalHide () {
      this.mapModal.show = false
    },
    showMapModal () {
      this.mapModal.show = true
    }
  }
}
</script>
