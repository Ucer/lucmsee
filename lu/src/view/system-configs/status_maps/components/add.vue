<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="600">
    <p slot="header">{{ $t('add') }}</p>
    <Form ref="formData" :model="formData" :rules="rules" label-position="left" :label-width="100">
      <FormItem label="表英文名" prop="table_name">
        <Input v-model="formData.table_name" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="表中文名" prop="table_name_cn">
        <Input v-model="formData.table_name_cn" placeholder="请输入"></Input>
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
} from '@/api/table'

export default {
  data () {
    return {
      modalShow: true,
      saveLoading: false,
      formData: {
        table_name: '',
        table_name_cn: '',
        remark: ''
      },
      rules: {
        table_name: [{
          required: true,
          message: '请填写表英文名称',
          trigger: 'blur'
        }],
        table_name_cn: [{
          required: true,
          message: '请填写表中文名称',
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
