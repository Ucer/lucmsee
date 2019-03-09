<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="70" class-name="article-modal">
    <p slot="header">{{ $t('add') }}</p>

    <Form ref="formData" :model="formData" :rules="rules" label-position="left" :label-width="100">
      <FormItem label="分类">
        <Select v-model="formData.article_category_id" filterable placeholder="请选择文章分类">
          <Option v-for="(item,key) in articleCategories" :value="item.id" :key="key">{{ item.name }} </Option>
        </Select>
      </FormItem>
      <FormItem label="标题" prop="title">
        <Input v-model="formData.title"></Input>
      </FormItem>
      <FormItem label="封面">
        <upload v-model="formData.cover_image" :upload-config="imguploadConfig" @on-upload-change='uploadChange'></upload>
      </FormItem>
      <FormItem label="关键词" prop="keywords">
        <Input type="textarea" v-model="formData.keywords" placeholder="以英文逗号隔开"></Input>
      </FormItem>
      <FormItem label="描述" prop="description">
        <Input type="textarea" v-model="formData.descriptions" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="状态">
        <RadioGroup v-model="formData.enable">
          <Radio v-for="(item,key) in tableStatus_enable" :key="key" :label="key">{{ item }}</Radio>
        </RadioGroup>
      </FormItem>
      <FormItem label="置顶">
        <RadioGroup v-model="formData.top">
          <Radio v-for="(item,key) in tableStatus_top" :key="key" :label="key">{{ item }}</Radio>
        </RadioGroup>
      </FormItem>
      <FormItem label="推荐">
        <RadioGroup v-model="formData.recommend">
          <Radio v-for="(item,key) in tableStatus_recommend" :key="key" :label="key">{{ item }}</Radio>
        </RadioGroup>
      </FormItem>
      <FormItem label="标签">
        <Select v-model="formData.tags" multiple filterable placeholder="请选择文章标签" style="width: auto">
          <Option v-for="item in articleTags" :value="item.id" :key="item.id">{{ item.name }} </Option>
        </Select>
      </FormItem>
      <FormItem label="新建标签">
        <Input v-model="newTagName" search enter-button="新建" placeholder="标签名字" @on-search="addTagExcute" style="width: auto"></Input>
        <input-helper styleClass="input-helper-error" text="标签不存在时可在此处添加"></input-helper>
      </FormItem>
      <FormItem label="访问权限">
        <Icon type="eye"></Icon><b>{{ Openness }}</b>
        <Button v-show="!editOpenness" size="small" type="text" @click="handleEditOpenness"><a>修改</a></Button>
        <transition name="openness-con">
          <div v-show="editOpenness" class="publish-time-picker-con">
            <RadioGroup v-model="formData.access_type" vertical>
              <Radio label="pub" title="所有人可见"> 公开</Radio>
              <Radio label="pwd" title="需要密码验证"> 密码
                <Input v-show="formData.access_type === 'pwd'" v-model="formData.access_value" style="width:50%" size="small" placeholder="请输入密码" />
              </Radio>
              <Radio label="pri">私密</Radio>
            </RadioGroup>
            <div>
              <Button type="primary" @click="handleSaveOpenness">保存</Button>
            </div>
          </div>
        </transition>
      </FormItem>
      <FormItem label="排序：">
        <Input v-model="formData.weight" placeholder="请输入序号" style="width: auto"></Input>
      </FormItem>
      <FormItem label="文章内容">
        <wang-editor v-model="formData.content" @on-change="editContentChange" :upload-config='wangUploadConfig'></wang-editor>
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
} from '@/api/article'
import Upload from '_c/common/upload'
import WangEditor from '_c/common/wang-editor'
import {
  addTag,
  getTagList
} from '@/api/tag'
import InputHelper from '_c/common/input-helper'

export default {
  props: ['articleCategories', 'tableStatus_enable', 'tableStatus_recommend', 'tableStatus_top'],
  components: {
    Upload,
    WangEditor,
    InputHelper
  },
  data() {
    const validatePassword = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入登录密码'))
      } else {
        if (this.formData.password !== '') {
          // 对第二个密码框单独验证
          this.$refs.formData.validateField('password_confirmation')
        }
        callback()
      }
    }
    const validatePasswordConfirm = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入确认密码'))
      } else if (value !== this.formData.password) {
        callback(new Error('两次密码不一致 '))
      } else {
        callback()
      }
    }
    return {
      modalShow: true,
      saveLoading: false,
      editOpenness: false,
      Openness: '公开',
      articleTags: [],
      newTagName: '',
      formData: {
        title: '',
        cover_image: {
          attachment_id: 0,
          url: '',
        },
        enable: 'F',
        keywords: '',
        description: '',
        content: '',
        article_category_id: 0,
        weight: 50,
        top: 'F',
        recommend: 'F',
        access_type: 'pub',
        access_value: '',
        tags: 0,
      },
      imguploadConfig: {
        headers: {
          'Authorization': window.access_token
        },
        format: ['jpg', 'jpeg', 'png', 'gif'],
        max_size: 500,
        upload_url: window.uploadUrl.uploadToLocaleUrl + '/pic/avatar',
        file_name: 'file',
        multiple: false,
        file_num: 1,
        data: {},
        default_list: []
      },
      wangUploadConfig: {
        headers: {
          'Authorization': window.access_token
        },
        wang_size: 1 * 1024 * 1024, // 1M
        uploadUrl: window.uploadUrl.uploadToLocaleUrl + '/pic/editor_article_content',
        params: {},
        max_length: 3,
        file_name: 'file',
        z_index: 10000,
        heightStyle: 'wang-editor-text-300'
      },
      rules: {
        title: [{
            required: true,
            message: '请填写文章标题',
            trigger: 'blur'
          },
          {
            type: 'string',
            min: 2,
            message: '文章标题至少要 2 个字符',
            trigger: 'blur'
          }
        ],
        article_category_id: [{
          required: true,
          message: '请选择分类',
          trigger: 'blur'
        }],
        content: [{
          required: true,
          message: '请填写文章内容',
          trigger: 'blur'
        }]
      },
    }
  },
  created() {
    this.getTagListExcute()
  },
  methods: {
    addExcute() {
      let t = this;
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
        } else {
          t.saveLoading = false
        }
      })
    },
    cancel() {
      this.modalShow = false
      this.$emit('on-add-modal-hide')
    },
    editContentChange(html, text) {
      // console.log(this.formData.content)
    },
    uploadChange(fileList, formatFileList) {},
    handleEditOpenness() {
      this.editOpenness = !this.editOpenness;
    },
    handleSaveOpenness() {
      var access_type = this.formData.access_type;
      if (this.passwordValidate()) {
        this.Openness = (access_type === 'pub') ? '公开' : (access_type === 'pwd') ? '密码' : '私密';
        this.editOpenness = false;
      }
    },
    passwordValidate() {
      var access_type = this.formData.access_type;
      var access_value = this.formData.access_value;
      if (access_type === 'pwd') {
        var patt = /^[a-zA-Z0-9]{4,8}$/;
        if (!patt.test(access_value)) {
          this.$Notice.error({
            title: '出错了',
            desc: '密码只能是4到8位的数字与字母'
          });
          return false;
        }

      }
      return true;
    },
    getTagListExcute() {
      let t = this;
      getTagList([]).then(res => {
        t.articleTags = res.data;
      })
    },
    addTagExcute() {
      let t = this;
      addTag({
        name: t.newTagName
      }).then(res => {
        t.getTagListExcute()
        t.$Notice.success({
          title: res.message
        })
      })
    }
  }
}
</script>
