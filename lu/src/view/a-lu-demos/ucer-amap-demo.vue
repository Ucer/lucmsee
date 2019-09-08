<template>
  <div>

    <h1>一、参数说明</h1>
    <Row>
      <Col span="12">
        <Table :columns="tableColumns" :data="tableData">
          <template slot-scope="{ row, index }" slot="default">
            <div v-html="row.default" style="overflow: auto"></div>
          </template>
        </Table>
        <h3>其它说明：</h3>
        <pre>
          可以传入初始位置、支持搜索、支持定位
        </pre>
      </Col>
    </Row>

    <h1>二、示例</h1>
    <Button type="info" size="small" style="margin-right: 5px" @click="showMapModal()">地图选点</Button>
    <ucer-map v-if='formdataFinished && mapModal.show' :width="600" :height="300" :amapData="formData.amapData"
              v-model="formData.amapData"
              @on-map-modal-hide="mapModalHide"></ucer-map>
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
      formdataFinished: false,
      formData: {
        amapData: {
          lng: 0,
          lat: 0
        }
      },
      mapModal: {
        show: false
      },
      tableColumns: [
        {
          title: '名称',
          key: 'name',
          minWidth: 100
        },
        {
          title: '类型',
          key: 'type',
          minWidth: 80
        },
        {
          title: '描述',
          key: 'description',
          tooltip: true,
          minWidth: 100
        },
        {
          title: '示例',
          key: 'default',
          slot: 'default',
          minWidth: 300
        }
      ],
      tableData: [
        {
          name: 'width',
          type: 'Integer',
          description: '地图宽度(px)',
          default: ':width="600"'
        },
        {
          name: 'height',
          type: 'Integer',
          description: '地图高度(px)',
          default: ':height="400"'
        },
        {
          name: 'amapData',
          type: 'Object',
          description: '地图经度纬度等信息',
          default: '<pre>{<br/>"lng":102.74335,<br/>"lat":25.06558,<br/>"address":"云南省昆明市盘龙区青云街道保利玺樾1期"<br/>}</pre>'
        }
      ]
    }
  },
  mounted () {
    this.getInfoByIdExcute()
  },
  methods: {
    getInfoByIdExcute () {
      let t = this
      t.formData.amapData = {
        lng: 0,
        lat: 0
      }
      t.formdataFinished = true
    },
    mapModalHide () {
      this.mapModal.show = false
    },
    showMapModal () {
      this.mapModal.show = true
    }
  }
}
</script>
