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
      <input-helper styleClass="input-helper-error"  text="英文字母与下划线组成"></input-helper>
  </div>
</template>
<script>
import InputHelper from '_c/common/input-helper'

export default {
  components: {
    InputHelper
  },
  data () {
    return {
      formData: {
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
          name: 'styleClass',
          type: 'String',
          description: '字体颜色样式',
          default: 'color-light-gray<br/>input-helper-default<br/>input-helper-info<br/>input-helper-error'
        },
        {
          name: 'type',
          type: 'String',
          description: 'iview图标',
          default: 'md-information'
        },
        {
          name: 'text',
          type: 'String',
          description: '提示内容',
          default: '特别提示'
        }
      ]
    }
  },
  mounted () {
  },
  methods: {
  }
}
</script>
