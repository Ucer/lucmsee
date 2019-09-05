<style>
  .search-box-wrapper {
    border: 1px solid;
  }
</style>
<template>
  <div class="">
    <Modal v-model="modalShow" :closable='false' :mask-closable='false' :width="mapData.width">
      <div class="amap-page-container">
        <el-amap-search-box class="search-box" :search-option="mapData.searchOption" :on-search-result="onSearchResult"></el-amap-search-box>
        <el-amap
          vid="amapDemo"
          :center="mapData.center"
          :zoom="mapData.zoom"
          :plugin="mapData.plugins"
          :events="mapData.events"
          class="amap-demo"
          :style="'height:'+mapData.height+'px'">
          <el-amap-marker v-for="(marker, index) in mapData.markers" :position="marker.position" :key="index" :events="marker.events" :visible="marker.visible" :draggable="marker.draggable" :vid="index"></el-amap-marker>
        </el-amap>
        <div class="toolbar">
          position: [{{ mapData.lng }}, {{ mapData.lat }}] address: {{ mapData.address }}
        </div>
      </div>
      <div slot="footer">
        <Button type="text" @click="cancel">取消</Button>
        <Button type="primary" @click="choseBtn">选择</Button>
      </div>
    </Modal>
  </div>
</template>

<script>

export default {
  props: {
    amapData: {
      lng: 0,
      lat: 0,
      address: ''
    }
  },
  data () {
    let t = this
    return {
      modalShow: true,
      mapData: {
        width: 600,
        height: 400,
        zoom: 12,
        center: [121.59996, 31.197646],
        address: '',
        lng: 0,
        lat: 0,
        citycode: '',
        adcode: '',
        province: '',
        city: '',
        district: '',
        township: '',
        street: '',
        streetNumber: '',
        searchOption: {
          city: '昆明',
          citylimit: false
        },
        markers: [
          {
            position: [121.5273285, 31.21515044],
            events: {
              click: () => {
                // alert('click marker')
              },
              dragend: (e) => {
                console.log('---event---: dragend')
                this.markers[0].position = [e.lnglat.lng, e.lnglat.lat]
              }
            },
            visible: true,
            draggable: false,
            template: '<span>1</span>'
          }
        ],
        events: {
          click (e) {
            let { lng, lat } = e.lnglat
            t.mapData.lng = lng
            t.mapData.lat = lat

            // 这里通过高德 SDK 完成。
            var geocoder = new AMap.Geocoder({
              radius: 1000,
              extensions: 'all'
            })
            geocoder.getAddress([lng, lat], function (status, result) {
              if (status === 'complete' && result.info === 'OK') {
                if (result && result.regeocode) {
                  t.mapData.address = result.regeocode.formattedAddress
                  t.$nextTick()
                }
              }
            })
            t.addMarker(t.mapData.lng, t.mapData.lat)
          }
        },
        plugins: [
          {
            pName: 'Geolocation',
            events: {
              init (o) {
              // o 是高德地图定位插件实例
                o.getCurrentPosition((status, result) => {
                  if (result && result.position) {
                    if (t.amapData.lng) { // 如果有伟入初始位置
                      t.mapData.lng = t.amapData.lng
                      t.mapData.lat = t.amapData.lat

                      t.mapData.center = [t.mapData.lng, t.mapData.lat]
                      t.addMarker(t.mapData.lng, t.mapData.lat)
                      return false
                    }

                    t.mapData.lng = result.position.lng
                    t.mapData.lat = result.position.lat
                    t.mapData.center = [t.mapData.lng, t.mapData.lat]
                    t.addMarker(t.mapData.lng, t.mapData.lat)

                    // 这里通过高德 SDK 完成。
                    var geocoder = new AMap.Geocoder({
                      radius: 1000,
                      extensions: 'all'
                    })
                    geocoder.getAddress([t.mapData.lng, t.mapData.lat], function (status, result) {
                      if (status === 'complete' && result.info === 'OK') {
                        if (result && result.regeocode) {
                          t.mapData.address = result.regeocode.formattedAddress
                          // 高德返回
                          t.mapData.citycode = result.regeocode.addressComponent.citycode
                          t.mapData.adcode = result.regeocode.addressComponent.adcode
                          t.mapData.province = result.regeocode.addressComponent.province
                          t.mapData.city = result.regeocode.addressComponent.city
                          t.mapData.district = result.regeocode.addressComponent.district
                          t.mapData.township = result.regeocode.addressComponent.township
                          t.mapData.street = result.regeocode.addressComponent.street
                          t.mapData.streetNumber = result.regeocode.addressComponent.streetNumber
                          t.$nextTick()
                        }
                      }
                    })
                  }
                })
              }
            }
          }
        ]
      }
    }
  },
  methods: {
    cancel () {
      this.modalShow = false
      this.$emit('on-map-modal-hide')
    },
    onSearchResult (pois) {
      let latSum = 0
      let lngSum = 0
      if (pois.length > 0) {
        pois.forEach(poi => {
          let { lng, lat } = poi
          lngSum += lng
          latSum += lat
        })
        let center = {
          lng: lngSum / pois.length,
          lat: latSum / pois.length
        }
        this.mapData.center = [center.lng, center.lat]
        this.addMarker()
      }
    },
    addMarker (lng, lat) {
      this.mapData.markers[0]['position'] = [lng, lat]
    },
    choseBtn () {
      this.$emit('input', this.mapData)
      this.cancel()
    }
  }
}
</script>
