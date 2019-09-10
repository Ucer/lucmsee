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

    <Table editable size="small" :columns="columns" :data="dataList" >
      <template slot-scope="{ row, index }" slot="weight">
        <table-edit table="article_categories" column="weight" :id="row.id" :value="row.weight" :index="index"></table-edit>
      </template>
    </Table>
  </div>
</template>
<script>
import TableEdit from '_c/common/table-edit'

export default {
  components: {
    TableEdit
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
          name: 'table',
          type: 'String',
          description: '表名',
          default: 'article_categories'
        },
        {
          name: 'column',
          type: 'String',
          description: '字段名',
          default: 'weight'
        },
        {
          name: 'id',
          type: 'Integer',
          description: '表id',
          default: '12'
        },
        {
          name: 'value',
          type: 'String',
          description: '新的值',
          default: '15'
        },
        {
          name: 'index',
          type: 'String',
          description: '在表格中的第几行',
          default: '1'
        }
      ],
      columns: [
        {
          title: 'id',
          key: 'id',
          minWidth: 100
        },
        {
          title: '排序',
          key: 'weight',
          minWidth: 80,
          slot: 'weight'
        }
      ],
      dataList: [
        {
          id: 1,
          weight: 12
        },
        {
          id: 2,
          weight: 13
        },
        {
          id: 3,
          weight: 14
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
