<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="600">
    <p slot="header">{{ $t('edit') }}</p>
    <Form ref="formData" :model="formData" :rules="rules" label-position="left" :label-width="100">
      <FormItem label="字段名" prop="column">
        <Input v-model="formData.column" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="状态码" prop="status_code">
        <Input v-model="formData.status_code" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="状态说明" prop="status_description">
        <Input v-model="formData.status_description" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="备注">
        <Input type="textarea" :rows="3" v-model="formData.remark" placeholder="请输入"></Input>
      </FormItem>
    </Form>
    <div slot="footer">
      <Button type="text" @click="cancel">{{ $t('cancel') }}</Button>
      <Button type="primary" @click="editExcute" :loading='saveLoading'>{{ $t('save') }} </Button>
    </div>
    <div class="demo-spin-container" v-if='spinLoading === true'>
      <Spin fix>
        <Icon type="load-c" size=18 class="spin-icon-load"></Icon>
        <div>{{ $t('table_loading') }}</div>
      </Spin>
    </div>
  </Modal>
</div>
</template>
<script>
import {
  edit,
  getInfoById
} from '@/api/status_map'

export default {
  props: {
    modalId: {
      type: Number,
      default: 0
    }
  },
  data () {
    return {
      modalShow: true,
      saveLoading: false,
      spinLoading: true,
      formData: {
        table_name: '',
        column: '',
        status_code: '',
        status_description: '',
        remark: ''
      },
      rules: {
        column: [{
          required: true,
          message: '请填写字段名',
          trigger: 'blur'
        }],
        status_code: [{
          required: true,
          message: '状态码',
          trigger: 'blur'
        }],
        status_description: [{
          required: true,
          message: '状态说明',
          trigger: 'blur'
        }] }
    }
  },
  mounted () {
    if (this.modalId > 0) {
      this.getInfoByIdExcute()
    }
  },
  methods: {
    getInfoByIdExcute () {
      let t = this
      getInfoById(t.modalId).then(res => {
        let res_data = res.data
        t.formData = {
          id: res_data.id,
          table_name: res_data.table_name,
          column: res_data.column,
          status_code: res_data.status_code,
          status_description: res_data.status_description,
          remark: res_data.remark
        }
        t.spinLoading = false
      })
    },
    editExcute () {
      let t = this
      t.$refs.formData.validate((valid) => {
        if (valid) {
          t.saveLoading = true
          edit(t.formData, t.formData.id).then(res => {
            t.saveLoading = false
            t.modalShow = false
            t.$emit('on-edit-modal-success')
            t.$emit('on-edit-modal-hide')
            t.$Notice.success({
              title: res.message
            })
          }, function (error) {
            t.saveLoading = false
          })
        }
      })
    },
    cancel () {
      this.modalShow = false
      this.$emit('on-edit-modal-hide')
    }
  }
}
</script>
