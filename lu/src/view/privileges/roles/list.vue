<template>
<div>
  <Row :gutter="24">
    <Col :xs="8" :lg="16">
    <Button type="success" icon="plus" @click="addBtn()">{{ $t('add') }}</Button>
    </Col>
    <Col :xs="12" :lg="4" class="hidden-mobile">
    <Input icon="search" placeholder="请输入角色名称..." v-model="searchForm.name" />
    </Col>
    <Col :xs="3" :lg="3" class="hidden-mobile">
    <Button type="primary" icon="ios-search" @click="getTableDataExcute()">{{ $t('search') }}</Button>
    </Col>
  </Row>
  <br>
  <Row>
    <div class="demo-spin-container" v-if="tableLoading">
      <Spin fix>
        <Icon type="load-c" size=18 class="spin-icon-load"></Icon>
        <div>{{ $t('table_loading') }}</div>
      </Spin>
    </div>
    <Table border :columns="columns" :data="dataList" @on-sort-change='onSortChange'>
      <template slot-scope="{ row, index }" slot="action">
        <Button type="success" size="small" style="margin-right: 5px" @click="tableButtonEdit(row,index)">{{ $t('edit') }}</Button>
        <Button type="info" size="small" style="margin-right: 5px" @click="tableButtonGiveUserPermission(row,index)">{{ $t('permission') }}</Button>
        <Poptip confirm :title="'您确定要删除ID为：' + row.id + ' 的记录？'" @on-ok="tableButtonDestroyOk(row,index)"> <Button type='error' size="small" style="margin-right: 5px">{{ $t('destroy')}}</Button> </Poptip>
      </template>
    </Table>
  </Row>
  <Modal v-model="permissionModal.show" :closable='false' :mask-closable=false width="800">
    <h3 slot="header" style="color:#2D8CF0">分配权限</h3>
    <Transfer v-if="permissionModal.show" :data="permissionModal.allPermissions" :target-keys="permissionModal.hasPermissions" :render-format="renderFormat" :operations="['移除权限','添加权限']" :list-style="permissionModal.listStyle" filterable @on-change="handleTransferChange">
    </Transfer>
    <div slot="footer">
      <Button type="text" @click="cancelPermissionModal">{{ $t('cancel') }}</Button>
      <Button type="primary" @click="giveRolePermissionExcute">{{ $t('save') }} </Button>
    </div>
  </Modal>
  <add-component v-if='addModal.show' @on-add-modal-success='getTableDataExcute' @on-add-modal-hide="addModalHide"></add-component>
  <edit-component v-if='editModal.show' :modal-id='editModal.id' @on-edit-modal-success='getTableDataExcute' @on-edit-modal-hide="editModalHide"> </edit-component>

</div>
</template>

<script>
import AddComponent from './components/add'
import EditComponent from './components/edit'

import {
  getAllPermission,
  getTableData,
  getRolePermissions,
  giveRolePermission,
  destroy
} from '@/api/role'

export default {
  components: {
    AddComponent,
    EditComponent
  },
  data() {
    return {
      searchForm: {
        order_by: 'id,desc'
      },
      notRealySortKey:[],
      tableLoading: true,
      dataList: [],
      permissionModal: {
        id: 0,
        allPermissions: [],
        hasPermissions: [],
        show: false,
        saveLoading: false,
        listStyle: {
          width: '250px',
          height: '300px'
        }
      },
      addModal: {
        show: false
      },
      editModal: {
        show: false,
        id: 0
      },
      columns: [{
          title: 'ID',
          key: 'id',
          sortable: 'customer',
          minWidth: 100,
        },
        {
          title: '角色限名称',
          key: 'name',
          minWidth: 150,
        },
        {
          title: '角色看守器',
          key: 'guard_name',
          minWidth: 150,
        },
        {
          title: '角色描述',
          key: 'description',
          minWidth: 150,
        },
        {
          title: '创建时间',
          key: 'created_at',
          minWidth: 150,
        },
        {
          title: '更新时间',
          key: 'created_at',
          minWidth: 150,
        },
        {
          title: '操作',
          minWidth: 200,
          slot: 'action',
        }
      ]
    }
  },
  created() {
    let t = this
    t.getTableDataExcute()
    t.getAllPermissionExcute()
  },
  methods: {
    getTableDataExcute() {
      let t = this
      t.tableLoading = true
      getTableData(t.searchForm).then(res => {
        const response_data = res.data
        t.dataList = response_data
        t.tableLoading = false
      }, function(error) {
        t.tableLoading = false
      })
    },
    tableButtonEdit(row, index) {
      this.editModal.show = true
      this.editModal.id = row.id
    },
    tableButtonDestroyOk(row, index) {
      let t = this
      destroy(row.id).then(res => {
        t.feeds.data.splice(index, 1)
        t.$Notice.success({
          title: res.message
        })
      })
    },
    tableButtonGiveUserPermission(row, index) {
      let t = this
      t.getRolePermissionsExcute(row.id)
      t.permissionModal.show = true
      t.permissionModal.id = row.id
    },
    onSortChange: function(data) {
      const order = data.column.key + ',' + data.order
      if (oneOf(data.column.key, this.notRealySortKey)) {

      } else {
        this.searchForm.order_by = order
        this.getTableDataExcute(this.feeds.current_page)
      }
    },
    addBtn() {
      this.addModal.show = true
    },
    addModalHide() {
      this.addModal.show = false
    },
    editModalHide() {
      this.editModal.show = false
    },
    renderFormat(item) {
      return item.label + '「' + item.description + '」'
    },
    cancelPermissionModal() {
      let t = this
      t.permissionModal.show = false
      t.permissionModal.saveLoading = false
    },
    getAllPermissionExcute() {
      let t = this
      getAllPermission().then(res => {
        t.permissionModal.allPermissions = res.data
      }, function(error) {})
    },
    handleTransferChange(newTargetKeys) {
      this.permissionModal.hasPermissions = newTargetKeys
    },
    getRolePermissionsExcute(id) {
      let t = this
      getRolePermissions(id).then(res => {
        t.permissionModal.hasPermissions = res.data
      })
    },
    giveRolePermissionExcute() {
      let t = this
      giveRolePermission(t.permissionModal.id, t.permissionModal.hasPermissions).then(res => {
        t.$Notice.success({
          title: res.message
        })
        t.permissionModal.show = false
      })
    },
  }
}
</script>
