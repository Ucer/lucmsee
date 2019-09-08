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
        </pre>
      </Col>
    </Row>

    <h1>二、示例</h1>
    <Row>
      <Col span="12">
        <h3>1.分割线</h3>
        hr-line-0
        <hr class="hr-line-0"/>
        hr-line-1
        <hr class="hr-line-1"/>
        hr-line-2
        <hr class="hr-line-2"/>
        hr-line-3
        <hr class="hr-line-3"/>
        hr-line-4
        <hr class="hr-line-4"/>
        hr-line-5
        <hr class="hr-line-5"/>
        line-gray
        <hr class="line-gray"/>
      </Col>
      <Col span="10" class="margin-left-10">
        <h3>2.margin、padding、height</h3>
        <div class="margin-left-10">margin-left-10</div>
        <div class="margin-top-8">margin-top-20</div>
        <div class="margin-bottom-10">margin-bottom-10</div>
        <div class="margin-right-10">margin-right-10</div>
        <div class="padding-left-20">padding-left-20</div>
        <div class="height-100px">height-100px</div>
      </Col>
      <Col span="10" class="margin-left-10 margin-top-20">
        <h3>3.media</h3>
        <div class="hidden-mobile-ipad">hidden-mobile-ipad</div>
        <div class="hidden-mobile">hidden-mobile</div>
      </Col>
      <Col span="10" class="margin-left-10 margin-top-20">
        <h3>4.color</h3>
        <div class="bgcolor-primary">bgcolor-primary</div>
        <div class="color-primary">color-primary</div>
        <div class="bgcolor-success">bgcolor-success</div>
        <div class="color-success">color-success</div>
        <div class="bgcolor-warning">bgcolor-warning</div>
        <div class="color-warning">color-warning</div>
        <div class="bgcolor-error">bgcolor-error</div>
        <div class="color-error">color-error</div>
      </Col>
    </Row>
  </div>
</template>
<script>

export default {
  data () {
    return {
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
          name: 'class',
          type: 'String',
          description: 'css 样式',
          default: 'class="hr-line-1"'
        }
      ]
    }
  },
  mounted () {
  },
  methods: {}
}
</script>
