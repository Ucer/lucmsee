(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d21e8fc"],{d5a6:function(t,e,a){"use strict";a.r(e);var o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("Row",{attrs:{gutter:24}},[a("Col",{attrs:{xs:8,lg:16}},[a("Button",{attrs:{type:"success",icon:"plus"},on:{click:function(e){t.addBtn()}}},[t._v("添加")])],1),a("Col",{staticClass:"hidden-mobile",attrs:{xs:12,lg:4}},[a("Input",{attrs:{icon:"search",placeholder:"请输入角色名称..."},model:{value:t.searchForm.name,callback:function(e){t.$set(t.searchForm,"name",e)},expression:"searchForm.name"}})],1),a("Col",{staticClass:"hidden-mobile",attrs:{xs:3,lg:3}},[a("Button",{attrs:{type:"primary",icon:"ios-search"},on:{click:function(e){t.getTableDataExcute()}}},[t._v("Search")])],1)],1),a("br"),a("Row",[t.tableLoading?a("div",{staticClass:"demo-spin-container"},[a("Spin",{attrs:{fix:""}},[a("Icon",{staticClass:"spin-icon-load",attrs:{type:"load-c",size:"18"}}),a("div",[t._v("加载中...")])],1)],1):t._e(),a("Table",{attrs:{border:"",columns:t.columns,data:t.dataList},on:{"on-sort-change":t.onSortChange}})],1),a("Modal",{attrs:{closable:!1,"mask-closable":!1,width:"800"},model:{value:t.permissionModal.show,callback:function(e){t.$set(t.permissionModal,"show",e)},expression:"permissionModal.show"}},[a("h3",{staticStyle:{color:"#2D8CF0"},attrs:{slot:"header"},slot:"header"},[t._v("分配权限")]),t.permissionModal.show?a("Transfer",{attrs:{data:t.permissionModal.allPermissions,"target-keys":t.permissionModal.hasPermissions,"render-format":t.renderFormat,operations:["移除权限","添加权限"],"list-style":t.permissionModal.listStyle,filterable:""},on:{"on-change":t.handleTransferChange}}):t._e(),a("div",{attrs:{slot:"footer"},slot:"footer"},[a("Button",{attrs:{type:"text"},on:{click:t.cancelPermissionModal}},[t._v("取消")]),a("Button",{attrs:{type:"primary"},on:{click:t.giveRolePermissionExcute}},[t._v("保存 ")])],1)],1),t.addModal.show?a("add-component",{on:{"on-add-modal-success":t.getTableDataExcute,"on-add-modal-hide":t.addModalHide}}):t._e(),t.editModal.show?a("edit-component",{attrs:{"modal-id":t.editModal.id},on:{"on-edit-modal-success":t.getTableDataExcute,"on-edit-modal-hide":t.editModalHide}}):t._e()],1)},i=[],s=(a("7f7f"),function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("Modal",{attrs:{closable:!1,"mask-closable":!1,width:"600"},model:{value:t.modalShow,callback:function(e){t.modalShow=e},expression:"modalShow"}},[a("p",{attrs:{slot:"header"},slot:"header"},[t._v("添加")]),a("Form",{ref:"formData",attrs:{model:t.formData,rules:t.rules,"label-position":"left","label-width":100}},[a("FormItem",{attrs:{label:"角色名称",prop:"name"}},[a("Input",{attrs:{placeholder:"请输入"},model:{value:t.formData.name,callback:function(e){t.$set(t.formData,"name",e)},expression:"formData.name"}})],1),a("FormItem",{attrs:{label:"角色描述",prop:"description"}},[a("Input",{attrs:{type:"textarea",rows:3,placeholder:"请输入"},model:{value:t.formData.description,callback:function(e){t.$set(t.formData,"description",e)},expression:"formData.description"}})],1)],1),a("div",{attrs:{slot:"footer"},slot:"footer"},[a("Button",{attrs:{type:"text"},on:{click:t.cancel}},[t._v("取消")]),a("Button",{attrs:{type:"primary",loading:t.saveLoading},on:{click:t.addExcute}},[t._v("保存 ")])],1)],1)],1)}),n=[],r=a("f499"),l=a.n(r),d=a("66df"),c=function(t){return d["a"].request({url:"/api/admin/roles",params:{search_data:l()(t)},method:"get"})},m=function(){return d["a"].request({url:"/api/admin/all_permissions",method:"get"})},u=function(t){return d["a"].request({url:"/api/admin/roles",data:t,method:"post"})},p=function(t,e){return d["a"].request({url:"/api/admin/roles/"+e,data:t,method:"patch"})},h=function(t){return d["a"].request({url:"api/admin/roles/"+t+"/permissions",method:"get"})},f=function(t,e){return d["a"].request({url:"/api/admin/give/"+t+"/permissions",data:{permission:e},method:"post"})},v=function(t){return d["a"].request({url:"/api/admin/roles/"+t,method:"delete"})},g=function(t){return d["a"].request({url:"/api/admin/roles/"+t,method:"get"})},b={data:function(){return{modalShow:!0,saveLoading:!1,formData:{name:"",guard_name:"",description:""},rules:{name:[{required:!0,message:"请填写角色限名称",trigger:"blur"}]}}},methods:{addExcute:function(){var t=this;t.$refs.formData.validate(function(e){e&&(t.saveLoading=!0,u(t.formData).then(function(e){t.saveLoading=!1,t.modalShow=!1,t.$emit("on-add-modal-success"),t.$emit("on-add-modal-hide"),t.$Notice.success({title:e.message})},function(e){t.saveLoading=!1}))})},cancel:function(){this.modalShow=!1,this.$emit("on-add-modal-hide")}}},w=b,x=a("2877"),y=Object(x["a"])(w,s,n,!1,null,null,null);y.options.__file="add.vue";var _=y.exports,M=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("Modal",{attrs:{closable:!1,"mask-closable":!1,width:"600"},model:{value:t.modalShow,callback:function(e){t.modalShow=e},expression:"modalShow"}},[a("p",{attrs:{slot:"header"},slot:"header"},[t._v("修改")]),a("Form",{ref:"formData",attrs:{model:t.formData,rules:t.rules,"label-position":"left","label-width":100}},[a("FormItem",{attrs:{label:"角色名称",prop:"name"}},[a("Input",{attrs:{placeholder:"请输入"},model:{value:t.formData.name,callback:function(e){t.$set(t.formData,"name",e)},expression:"formData.name"}})],1),a("FormItem",{attrs:{label:"角色描述",prop:"description"}},[a("Input",{attrs:{type:"textarea",rows:3,placeholder:"请输入"},model:{value:t.formData.description,callback:function(e){t.$set(t.formData,"description",e)},expression:"formData.description"}})],1)],1),a("div",{attrs:{slot:"footer"},slot:"footer"},[a("Button",{attrs:{type:"text"},on:{click:t.cancel}},[t._v("取消")]),a("Button",{attrs:{type:"primary",loading:t.saveLoading},on:{click:t.editExcute}},[t._v("保存 ")])],1),!0===t.spinLoading?a("div",{staticClass:"demo-spin-container"},[a("Spin",{attrs:{fix:""}},[a("Icon",{staticClass:"spin-icon-load",attrs:{type:"load-c",size:"18"}}),a("div",[t._v("加载中...")])],1)],1):t._e()],1)],1)},k=[],D=(a("c5f6"),{props:{modalId:{type:Number,default:0}},data:function(){return{modalShow:!0,saveLoading:!1,spinLoading:!0,formData:{name:"",guard_name:"",description:""},rules:{name:[{required:!0,message:"请填写角色限名称",trigger:"blur"}]}}},mounted:function(){this.modalId>0&&this.getInfoByIdExcute()},methods:{getInfoByIdExcute:function(){var t=this;g(t.modalId).then(function(e){var a=e.data;t.formData={id:a.id,name:a.name,description:a.description},t.spinLoading=!1})},editExcute:function(){var t=this;t.$refs.formData.validate(function(e){e&&(t.saveLoading=!0,p(t.formData,t.formData.id).then(function(e){t.saveLoading=!1,t.modalShow=!1,t.$emit("on-edit-modal-success"),t.$emit("on-edit-modal-hide"),t.$Notice.success({title:e.message})},function(e){t.saveLoading=!1}))})},cancel:function(){this.modalShow=!1,this.$emit("on-edit-modal-hide")}}}),E=D,L=Object(x["a"])(E,M,k,!1,null,null,null);L.options.__file="edit.vue";var $=L.exports,S={components:{AddComponent:_,EditComponent:$},data:function(){var t=this;return{searchForm:{order_by:"id,desc"},tableLoading:!0,dataList:[],permissionModal:{id:0,allPermissions:[],hasPermissions:[],show:!1,saveLoading:!1,listStyle:{width:"250px",height:"300px"}},addModal:{show:!1},editModal:{show:!1,id:0},columns:[{title:"ID",key:"id",sortable:"customer",minWidth:100},{title:"角色限名称",key:"name",minWidth:150},{title:"角色看守器",key:"guard_name",minWidth:150},{title:"角色描述",key:"description",minWidth:150},{title:"创建时间",key:"created_at",minWidth:150},{title:"更新时间",key:"created_at",minWidth:150},{title:"操作",minWidth:200,render:function(e,a){var o=t;return e("div",[e("Button",{props:{type:"success",size:"small"},style:{marginRight:"5px"},on:{click:function(){t.editModal.show=!0,t.editModal.id=a.row.id}}},"修改"),e("Button",{props:{type:"info",size:"small"},style:{marginRight:"5px"},on:{click:function(){o.getRolePermissionsExcute(a.row.id),o.permissionModal.show=!0,o.permissionModal.id=a.row.id}}},"权限"),e("Poptip",{props:{confirm:!0,title:"您确定要删除「"+a.row.name+"」角色？",transfer:!0},on:{"on-ok":function(){o.destroyExcute(a.row.id,a.index)}}},[e("Button",{style:{margin:"0 5px"},props:{type:"error",size:"small",placement:"top"}},"删除")])])}}]}},created:function(){var t=this;t.getTableDataExcute(),t.getAllPermissionExcute()},methods:{renderFormat:function(t){return t.label+"「"+t.description+"」"},cancelPermissionModal:function(){var t=this;t.permissionModal.show=!1,t.permissionModal.saveLoading=!1},getAllPermissionExcute:function(){var t=this;m().then(function(e){t.permissionModal.allPermissions=e.data},function(t){})},getTableDataExcute:function(){var t=this;t.tableLoading=!0,c(t.searchForm).then(function(e){var a=e.data;t.dataList=a,t.tableLoading=!1},function(e){t.tableLoading=!1})},onSortChange:function(t){var e=t.column.key+","+t.order;this.searchForm.order_by=e,this.getTableDataExcute()},handleTransferChange:function(t){this.permissionModal.hasPermissions=t},getRolePermissionsExcute:function(t){var e=this;h(t).then(function(t){e.permissionModal.hasPermissions=t.data})},giveRolePermissionExcute:function(){var t=this;f(t.permissionModal.id,t.permissionModal.hasPermissions).then(function(e){t.$Notice.success({title:e.message}),t.permissionModal.show=!1})},destroyExcute:function(t,e){var a=this;v(t).then(function(t){a.dataList.splice(e,1),a.$Notice.success({title:t.message})})},addBtn:function(){this.addModal.show=!0},addModalHide:function(){this.addModal.show=!1},editModalHide:function(){this.editModal.show=!1}}},I=S,P=Object(x["a"])(I,o,i,!1,null,null,null);P.options.__file="list.vue";e["default"]=P.exports}}]);