<style lang="less">
.tables-edit-outer {
    height: 100%;
    .tables-edit-con {
        position: relative;
        height: 100%;
        .value-con {
            vertical-align: middle;
        }
        .tables-edit-btn {
            position: absolute;
            right: 10px;
            top: 0;
            display: none;
        }
        &:hover {
            .tables-edit-btn {
                display: inline-block;
            }
        }
    }
    .tables-editting-con {
        .tables-edit-input {
            width: ~"calc(100% - 60px)";
        }
    }
}
</style>
<template>
<div class="tables-edit-outer">
  <div v-if="!isEditting" class="tables-edit-con">
    <span class="value-con">{{ columnValue }}</span>
    <Button @click="startEdit" class="tables-edit-btn" style="padding: 2px 4px;" type="text">
      <Icon type="md-create"></Icon>
    </Button>
  </div>
  <div v-else class="tables-editting-con">
    <Input v-model="columnValue" class="tables-edit-input" />
    <Button @click="saveEdit" style="padding: 6px 4px;" type="text">
      <Icon type="md-checkmark"></Icon>
    </Button>
    <Button @click="canceltEdit" style="padding: 6px 4px;" type="text">
      <Icon type="md-close"></Icon>
    </Button>
  </div>
</div>
</template>

<script>
import {
  commonEditTableColumn
} from '@/api/common'

export default {
  name: 'TablesEdit',
  props: ['table', 'column', 'id', 'value', 'index'],
  data () {
    return {
      edittingCellId: '',
      columnValue: this.value
    }
  },
  computed: {
    isEditting () {
      return this.edittingCellId === `editting-${this.column}-${this.id}`
    }
  },
  methods: {
    startEdit () {
      this.edittingCellId = `editting-${this.column}-${this.id}`
    },
    saveEdit () {
      this.commonEditTableColumnExcute()
    },
    canceltEdit () {
      this.edittingCellId = ''
    },
    commonEditTableColumnExcute () {
      let t = this
      commonEditTableColumn(t.id, t.table, t.column, t.columnValue).then(res => {
        t.edittingCellId = ''
        // t.value = t.columnValue
      })
    }
  }
}
</script>
