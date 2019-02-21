<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="600">
    <p slot="header">{{ $t('edit') }}</p>
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
} from '@/api/table'

export default {
  props: {
    modalId: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      modalShow: true,
      saveLoading: false,
      spinLoading: true,
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
        }],
      },
    }
  },
  mounted() {
    if (this.modalId > 0) {
      this.getInfoByIdExcute()
    }
  },
  methods: {
    getInfoByIdExcute() {
      let t = this;
      getInfoById(t.modalId).then(res => {
        let res_data = res.data
        t.formData = {
          id: res_data.id,
          table_name: res_data.table_name,
          table_name_cn: res_data.table_name_cn,
          remark: res_data.remark
        }
        t.spinLoading = false;
      })

    },
    editExcute() {
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
          }, function(error) {
            t.saveLoading = false;
          })
        }
      })
    },
    cancel() {
      this.modalShow = false
      this.$emit('on-edit-modal-hide')
    }
  }
}
</script>
