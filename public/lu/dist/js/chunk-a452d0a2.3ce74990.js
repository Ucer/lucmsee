(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-a452d0a2"],{b5bc:function(e,t,a){"use strict";var n=a("c23a"),i=a.n(n);i.a},c23a:function(e,t,a){},f73e:function(e,t,a){"use strict";a.r(t);var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("Row",[a("i-col",[a("Card",[a("Row",[a("i-col",{attrs:{span:"8"}},[a("Button",{attrs:{type:"primary"},on:{click:e.showModal}},[e._v("显示可拖动弹窗")]),a("br"),a("Button",{directives:[{name:"draggable",rawName:"v-draggable",value:e.buttonOptions,expression:"buttonOptions"}],staticClass:"draggable-btn"},[e._v("这个按钮也是可以拖动的")])],1),a("i-col",{attrs:{span:"16"}},[a("div",{staticClass:"intro-con"},[e._v('\n              <Modal v-draggable="options" v-model="visible">标题</Modal>\n              '),a("pre",{staticClass:"code-con"},[e._v("  options = {\n    trigger: '.ivu-modal-body',\n    body: '.ivu-modal'\n  }\n              ")])])])],1)],1)],1),a("Modal",{directives:[{name:"draggable",rawName:"v-draggable",value:e.options,expression:"options"}],model:{value:e.modalVisible,callback:function(t){e.modalVisible=t},expression:"modalVisible"}},[e._v("\n      拖动这里即可拖动整个弹窗\n    ")])],1),a("Row",{staticStyle:{"margin-top":"10px"}},[a("i-col",[a("Card",[a("Row",[a("i-col",{attrs:{span:"8"}},[a("Input",{staticStyle:{width:"60%"},model:{value:e.inputValue,callback:function(t){e.inputValue=t},expression:"inputValue"}},[a("Button",{directives:[{name:"clipboard",rawName:"v-clipboard",value:e.clipOptions,expression:"clipOptions"}],attrs:{slot:"append"},slot:"append"},[e._v("copy")])],1)],1),a("i-col",{attrs:{span:"16"}},[a("div",{staticClass:"intro-con"},[e._v('\n              <Input style="width: 60%" v-model="inputValue">\n                '),a("br"),e._v('\n                   <Button slot="append" v-clipboard="clipOptions">copy</Button>\n                '),a("br"),e._v("\n              </Input>\n              "),a("pre",{staticClass:"code-con"},[e._v("  clipOptions: {\n    value: this.inputValue,\n    success: (e) => {\n      this.$Message.success('复制成功')\n    },\n    error: () => {\n      this.$Message.error('复制失败')\n    }\n  }\n              ")])])])],1)],1)],1),a("Modal",{directives:[{name:"draggable",rawName:"v-draggable",value:e.options,expression:"options"}],model:{value:e.modalVisible,callback:function(t){e.modalVisible=t},expression:"modalVisible"}},[e._v("\n      拖动这里即可拖动整个弹窗\n    ")])],1)],1)},i=[],o={name:"directive_page",data:function(){return{modalVisible:!1,options:{trigger:".ivu-modal-body",body:".ivu-modal",recover:!0},buttonOptions:{trigger:".draggable-btn",body:".draggable-btn"},statu:1,inputValue:"这是输入的内容"}},computed:{clipOptions:function(){var e=this;return{value:this.inputValue,success:function(t){e.$Message.success("复制成功")},error:function(){e.$Message.error("复制失败")}}}},methods:{showModal:function(){this.modalVisible=!0}}},s=o,l=(a("b5bc"),a("25c1")),r=Object(l["a"])(s,n,i,!1,null,null,null);r.options.__file="directive.vue";t["default"]=r.exports}}]);