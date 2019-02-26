<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="600">
    <p slot="header">{{ $t('edit') }}</p>
    <Form ref="formData" :model="formData" :rules="rules" label-position="left" :label-width="100">
      <FormItem label="分组：" prop="config_group">
        <Select v-model="formData.config_group" filterable placeholder="请选择配置分组">
          <Option v-for="(item,key) in config_group" :value="key" :key="key">{{ item.title }} </Option>
        </Select>
      </FormItem>
      <FormItem label="配置标识：" prop="flag">
        <Input v-model="formData.flag" placeholder="请输入配置标识"></Input>
        <input-helper text="英文字母与下划线组成"></input-helper>
      </FormItem>
      <FormItem label="配置标题：" prop="title">
        <Input v-model="formData.title" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="配置值：">
        <Input type="textarea" :rows="3" v-model="formData.value" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="是否启用：">
        <RadioGroup v-model="formData.enable">
          <Radio label="F">禁用</Radio>
          <Radio label="T">启用</Radio>
        </RadioGroup>
      </FormItem>
      <FormItem label="描述：">
        <Input type="textarea" :rows="3" v-model="formData.description" placeholder="请输入"></Input>
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
} from '@/api/system_config'
import InputHelper from '_c/common/input-helper'

export default {
  components: {
    InputHelper
  },
  props: {
    modalId: {
      type: Number,
      default: 0
    },
    config_group: {
      type: Object,
      value: []
    }
  },
  data() {
    return {
      modalShow: true,
      saveLoading: false,
      spinLoading: true,
      formData: {
        flag: '',
        title: '',
        config_group: '',
        value: '',
        description: '',
        enable: 'T'
      },
      rules: {
        flag: [{
          required: true,
          message: '请填写配置标识',
          trigger: 'blur'
        }],
        title: [{
          required: true,
          message: '请填写配置标题',
          trigger: 'blur'
        }],
        config_group: [{
          required: true,
          message: '请选择配置分组',
          trigger: 'blur'
        }]
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
          config_group: res_data.config_group,
          flag: res_data.flag,
          title: res_data.title,
          value: res_data.value,
          enable: res_data.enable,
          description: res_data.description
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
