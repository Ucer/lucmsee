<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="600">
    <p slot="header">{{ $t('add') }}</p>
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
      <Button type="primary" @click="addExcute" :loading='saveLoading'>{{ $t('save') }} </Button>
    </div>
  </Modal>
</div>
</template>
<script>
import {
  add
} from '@/api/status_map'

export default {
  props: {
    tableName: {
      default: ''
    }
  },
  data () {
    return {
      modalShow: true,
      saveLoading: false,
      formData: {
        table_name: this.tableName,
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
        }]
      }
    }
  },
  methods: {
    addExcute () {
      let t = this
      t.$refs.formData.validate((valid) => {
        if (valid) {
          t.saveLoading = true
          add(t.formData).then(res => {
            t.saveLoading = false
            t.modalShow = false
            t.$emit('on-add-modal-success')
            t.$emit('on-add-modal-hide')
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
      this.$emit('on-add-modal-hide')
    }
  }
}
</script>
