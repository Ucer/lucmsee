(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0400d0c8"],{1179:function(t,a,e){var n=e("e912"),o=e("2aa6");n(n.G+n.F*(parseInt!=o),{parseInt:o})},"2aa6":function(t,a,e){var n=e("ca91").parseInt,o=e("6b99").trim,r=e("7298"),s=/^[-+]?0[xX]/;t.exports=8!==n(r+"08")||22!==n(r+"0x16")?function(t,a){var e=o(String(t),3);return n(e,a>>>0||(s.test(e)?16:10))}:n},a6eb:function(t,a,e){t.exports=e("c2d4")},c2d4:function(t,a,e){e("1179"),t.exports=e("332a").parseInt},dc64:function(t,a,e){"use strict";e.r(a);var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",[e("Card",{attrs:{shadow:""}},[e("Row",[e("i-col",{attrs:{span:"4"}},[e("Button",{on:{click:t.createTagParams}},[t._v("添加一个标签")])],1),e("i-col",{attrs:{span:"20"}},[e("p",[t._v("动态路由，添加params")])])],1)],1),e("Card",{staticStyle:{"margin-top":"10px"},attrs:{shadow:""}},[e("Row",[e("i-col",{attrs:{span:"4"}},[e("Button",{on:{click:t.createTagQuery}},[t._v("添加一个标签")])],1),e("i-col",{attrs:{span:"20"}},[e("p",[t._v("动态路由，添加query")])])],1)],1),e("Card",{staticStyle:{"margin-top":"10px"},attrs:{shadow:""}},[e("Row",[e("i-col",{attrs:{span:"4"}},[e("Button",{on:{click:t.handleCloseTag}},[t._v("关闭工具方法页")])],1),e("i-col",{attrs:{span:"20"}},[e("p",[t._v("手动关闭页面")])])],1)],1)],1)},o=[],r=e("a6eb"),s=e.n(r),c=e("de57"),i=e("f2de"),p={name:"tools_methods_page",methods:Object(c["a"])({},Object(i["d"])(["closeTag"]),{createTagParams:function(){var t=s()(1e5*Math.random()),a={name:"params",params:{id:t},meta:{title:"动态路由-".concat(t)}};this.$router.push(a)},createTagQuery:function(){var t=s()(1e5*Math.random()),a={name:"query",query:{id:t},meta:{title:"参数-".concat(t)}};this.$router.push(a)},handleCloseTag:function(){this.closeTag({name:"tools_methods_page"})}})},l=p,u=e("25c1"),d=Object(u["a"])(l,n,o,!1,null,null,null);d.options.__file="tools-methods.vue";a["default"]=d.exports}}]);