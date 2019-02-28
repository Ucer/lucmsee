<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="600">
    <p slot="header">{{ $t('add') }}</p>
    <Form ref="formData" :model="formData" :rules="rules" label-position="left" :label-width="100">
      <FormItem label="上级分类：">
        <Select v-model="formData.pid" filterable placeholder="请选择上级分类">
            <Option :value="0">顶级分类 </Option>
            <Option v-for="(item,key) in articleCategories" :value="item.id">{{ item.name }} </Option>
        </Select>
      </FormItem>
      <FormItem label="分类名称">
        <Input v-model="formData.name" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="启用状态：">
        <RadioGroup v-model="formData.enable">
          <Radio v-for="(item,key) in tableStatus_enable" :label="key">{{ item }}</Radio>
        </RadioGroup>
      </FormItem>
      <FormItem label="分类描述" prop="description">
        <Input type="textarea" :rows="3" v-model="formData.description" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="排序：">
        <Input v-model="formData.weight" placeholder="请输入序号"></Input>
      </FormItem>
    </Form>
    <div slot="footer">
      <Button type="text" @click="cancel">{{ $t('cancel') }}</Button>
      <Button type="primary" @click="addExcute" :loading='saveLoading'>{{ $t('save') }} </Button>
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
  add,
  getAllCategories
} from '@/api/article_category'

export default {
  props: ['tableStatus_enable'],
  data() {
    return {
      modalShow: true,
      saveLoading: false,
      articleCategories: [],
      saveLoading: false,
      spinLoading: true,
      formData: {
        name: '',
        enable: 'T',
        pid: 0,
        description: '',
        weight: 50,
      },
      rules: {
        name: [{
          required: true,
          message: '请填写权限名称',
          trigger: 'blur'
        }],
      },
    }
  },
  mounted() {
    this.getAllCategoriesExcute()
  },
  methods: {
    addExcute() {
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
          }, function(error) {
            t.saveLoading = false;
          })
        }
      })
    },
    cancel() {
      this.modalShow = false
      this.$emit('on-add-modal-hide')
    },
    getAllCategoriesExcute() {
      let t = this
      getAllCategories().then(res => {
        t.articleCategories = res.data
        t.spinLoading = false
      })
    },
  }
}
</script>
