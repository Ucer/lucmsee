<template>
<div>
  <Modal v-model="modalShow" :closable='false' :mask-closable=false width="70" class-name="article-modal">
    <p slot="header">修改</p>
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
        <upload v-if='formdataFinished' v-model="formData.cover_image" :upload-config="imguploadConfig" @on-upload-change='uploadChange'></upload>
      </FormItem>
      <FormItem label="关键词" prop="keywords">
        <Input type="textarea" v-model="formData.keywords" placeholder="以英文逗号隔开"></Input>
      </FormItem>
      <FormItem label="描述" prop="description">
        <Input type="textarea" v-model="formData.description" placeholder="请输入"></Input>
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
        <markdown-editor v-if="formdataFinished" :cache="false" v-model="formData.content" :value="formData.content" :upload_url="markdownEditorUploadUrl" />
      </FormItem>
    </Form>
    <div slot="footer">
      <Button type="text" @click="cancel">取消</Button>
      <Button type="primary" @click="editExcute" :loading='saveLoading'>保存
      </Button>
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
} from '@/api/article'
import Upload from '_c/common/upload'
import MarkdownEditor from '_c/markdown-editor'
import {
  addTag,
  getTagList
} from '@/api/tag'
import InputHelper from '_c/common/input-helper'
export default {
  props: ['modalId', 'articleCategories', 'tableStatus_enable', 'tableStatus_recommend', 'tableStatus_top'],
  components: {
    Upload,
    MarkdownEditor,
    InputHelper
  },
  data () {
    return {
      modalShow: true,
      saveLoading: false,
      spinLoading: true,
      editOpenness: false,
      Openness: '公开',
      articleTags: [],
      newTagName: '',
      markdownEditorUploadUrl: window.uploadUrl.uploadToLocaleUrl + '/pic/markdown_editor_article_content',
      formData: {
        title: '',
        cover_image: {
          attachment_id: 0,
          url: ''
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
        tags: 0
      },
      formdataFinished: false,
      imguploadConfig: {
        headers: {
          'Authorization': window.access_token
        },
        format: ['jpg', 'jpeg', 'png', 'gif'],
        max_size: 500,
        upload_url: window.uploadUrl.uploadToLocaleUrl + '/pic/article_cover_img',
        file_name: 'file',
        multiple: false,
        file_num: 1,
        data: {},
        default_list: []
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
      }
    }
  },
  mounted () {
    this.getTagListExcute()
  },
  methods: {
    getInfoByIdExcute () {
      let t = this
      getInfoById(t.modalId).then(res => {
        let res_data = res.data
        t.formData = {
          id: res_data.id,
          title: res_data.title,
          cover_image: {
            attachment_id: 0,
            url: res_data.cover_image
          },
          enable: res_data.enable,
          keywords: res_data.keywords,
          description: res_data.description,
          content: res_data.content.raw,
          article_category_id: res_data.article_category_id,
          weight: res_data.weight,
          top: res_data.top,
          recommend: res_data.recommend,
          access_type: res_data.access_type,
          access_value: ''
        }
        t.handleSaveOpenness()
        t.imguploadConfig.default_list = [t.formData.cover_image]
        t.formData.tags = res_data.tagids

        t.formdataFinished = true
        t.spinLoading = false
      })
    },
    editExcute () {
      let t = this
      t.$refs.formData.validate((valid) => {
        if (valid) {
          t.saveLoading = true
          edit(t.formData, t.modalId).then(res => {
            t.saveLoading = false
            t.modalShow = false
            t.$emit('on-edit-modal-success')
            this.$emit('on-edit-modal-hide')
            t.$Notice.success({
              title: res.message
            })
          }, function (error) {
            t.saveLoading = false
          })
        } else {
          t.saveLoading = false
        }
      })
    },
    cancel () {
      this.modalShow = false
      this.$emit('on-edit-modal-hide')
    },
    editContentChange (html, text) {
      // console.log(this.formData.content)
    },
    uploadChange (fileList, formatFileList) {},
    handleEditOpenness () {
      this.editOpenness = !this.editOpenness
    },
    handleSaveOpenness () {
      var access_type = this.formData.access_type
      if (this.passwordValidate()) {
        this.Openness = (access_type === 'pub') ? '公开' : (access_type === 'pwd') ? '密码' : '私密'
        this.editOpenness = false
      }
    },
    passwordValidate () {
      var access_type = this.formData.access_type
      var access_value = this.formData.access_value
      if (access_type === 'pwd' && this.access_value) {
        var patt = /^[a-zA-Z0-9]{4,8}$/
        if (!patt.test(access_value)) {
          this.$Notice.error({
            title: '出错了',
            desc: '密码只能是4到8位的数字与字母'
          })
          return false
        }
      }
      return true
    },
    getTagListExcute () {
      let t = this
      getTagList([]).then(res => {
        t.articleTags = res.data
        if (this.modalId > 0) {
          this.getInfoByIdExcute()
        }
      })
    },
    addTagExcute () {
      let t = this
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
