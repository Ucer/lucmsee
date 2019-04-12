<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="600">
    <p slot="header">{{ $t('add') }}</p>
    <Form ref="formData" :model="formData" :rules="rules" label-position="left" :label-width="100">
      <FormItem label="标签名称" prop="name">
        <Input v-model="formData.name" placeholder="请输入"></Input>
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
} from '@/api/tag'

export default {
  data () {
    return {
      modalShow: true,
      saveLoading: false,
      formData: {
        name: ''
      },
      rules: {
        name: [{
          required: true,
          message: '请填写标签名称',
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
