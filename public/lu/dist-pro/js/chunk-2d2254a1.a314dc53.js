(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d2254a1"],{e484:function(e,t,a){"use strict";a.r(t);var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("Row",{attrs:{gutter:24}},[a("Col",{staticClass:"hidden-mobile",attrs:{xs:2,lg:2}},[a("Button",{attrs:{to:"/dashboard/databases",type:"primary"}},[a("Icon",{attrs:{type:"ios-arrow-back"}}),e._v(" 回到数据表页面\n    ")],1)],1),a("Col",{staticClass:"hidden-mobile",attrs:{xs:4,lg:4}},[a("Poptip",{attrs:{confirm:"",placement:"right",title:"确认要执行删除操作?","ok-text":"确认","cancel-text":"点错了"},on:{"on-ok":function(t){return e.destroyManyTableBakRecordExcute(e.selectIds,!1)}}},[a("Button",{attrs:{type:"warning"}},[e._v("\n        "+e._s(e.$t("destroy"))+"\n      ")])],1)],1)],1),a("br"),a("Row",[e.tableLoading?a("div",{staticClass:"demo-spin-container"},[a("Spin",{attrs:{fix:""}},[a("Icon",{staticClass:"spin-icon-load",attrs:{type:"load-c",size:"18"}}),a("div",[e._v(e._s(e.$t("table_loading")))])],1)],1):e._e(),a("Table",{attrs:{height:"600",size:"small",columns:e.columns,data:e.feeds.data},on:{"on-sort-change":e.onSortChange,"on-selection-change":e.onSelectionChange},scopedSlots:e._u([{key:"bak_tables_name",fn:function(t){var n=t.row;t.index;return[a("Poptip",{attrs:{slot:"content","word-wrap":"",width:"600",placement:"bottom",trigger:"hover",title:"备份的表"},slot:"content"},[a("Button",[e._v(e._s(n.first_table_name)+"..")]),a("div",{attrs:{slot:"content"},slot:"content"},e._l(n.bak_tables_name,function(t,n){return a("span",{key:n},[a("Tag",n%2==0?{attrs:{type:"border",color:"success"}}:{attrs:{type:"border",color:"error"}},[e._v(e._s(t))])],1)}),0)],1)]}},{key:"action",fn:function(t){var n=t.row,o=t.index;return[a("Button",{staticStyle:{"margin-right":"5px"},attrs:{type:"primary",size:"small",loading:e.loadingDownloadBtn===n.id},on:{click:function(t){return e.tableBakSqlFileDownloadExcute(n,o)}}},[e.loadingDownloadBtn===n.id?a("span",[e._v("下载中...")]):a("span",[e._v(e._s(e.$t("download")))])])]}}])}),a("div",{staticStyle:{margin:"10px",overflow:"hidden"}},[a("div",{staticStyle:{float:"right"}},[a("Page",{staticClass:"paging",attrs:{total:e.feeds.total,current:e.feeds.current_page,"page-size":e.feeds.per_page,"show-elevator":"","show-total":"","show-sizer":""},on:{"on-change":e.handleOnPageChange,"on-page-size-change":e.onPageSizeChange}})],1)])],1)],1)},o=[],i=a("66df"),s=function(e,t,a){return i["a"].request({url:"/api/admin/databases/table_bak_records",params:{page:e,per_page:t,search_data:JSON.stringify(a)},method:"get"})},r=function(e){return i["a"].request({url:"/api/admin/databases/"+e+"/table_bak_sql_file_download",method:"get",responseType:"blob"})},l=function(e,t){return i["a"].request({url:"/api/admin/databases/destroy_many_table_bak_record",data:{is_op_all:t,selectes:e},method:"delete"})},d=a("90de"),c={data:function(){return{searchForm:{order_by:"created_at,desc",table_name:"",column:""},notRealySortKey:[],tableLoading:!1,feeds:{data:[],total:0,current_page:1,per_page:10},selectIds:"",loadingDownloadBtn:0,bak_data_rows:0,columns:[{type:"selection",width:60,align:"center"},{title:"ID",key:"id",sortable:"customer",minWidth:100},{title:"备份的表",minWidth:100,slot:"bak_tables_name"},{title:"操作用户ID",key:"user_id",minWidth:100},{title:"文件大小",key:"file_size",minWidth:80,sortable:"customer"},{title:"备份产生文件数量",key:"file_num",minWidth:60},{title:"创建时间",key:"created_at",sortable:"customer",minWidth:150},{title:"修改时间",key:"updated_at",sortable:"customer",minWidth:150},{title:"操作",key:"",minWidth:200,slot:"action"}]}},created:function(){var e=this;e.getTableDataExcute(e.feeds.current_page)},methods:{handleOnPageChange:function(e){this.getTableDataExcute(e)},onPageSizeChange:function(e){this.feeds.per_page=e,this.getTableDataExcute(this.feeds.current_page)},getTableDataExcute:function(e){var t=this;t.tableLoading=!0,t.feeds.current_page=e,s(e,t.feeds.per_page,t.searchForm).then(function(e){t.feeds.data=e.data,t.feeds.total=e.meta.total,t.tableLoading=!1},function(e){t.tableLoading=!1})},onSortChange:function(e){var t=e.column.key+","+e.order;Object(d["i"])(e.column.key,this.notRealySortKey)||(this.searchForm.order_by=t,this.getTableDataExcute(this.feeds.current_page))},onSelectionChange:function(e){for(var t in this.selectIds="",e)this.selectIds+=","+e[t].id},destroyManyTableBakRecordExcute:function(e,t){if(!1===t&&!e)return this.$Notice.error({title:"出错了",desc:"请先选择要操作的项"}),!1;var a=this;l(e,t).then(function(e){a.$Notice.success({title:e.message}),a.getTableDataExcute(a.feeds.current_page)})},tableBakSqlFileDownloadExcute:function(e,t){var a=this;a.loadingDownloadBtn=e.id,r(e.id).then(function(e){var t=e,n=new Blob([t]),o=Object(d["c"])("-",""),i=o[0]+""+o[1]+".zip";if("download"in document.createElement("a")){var s=document.createElement("a");s.download=i,s.style.display="none",s.href=URL.createObjectURL(n),document.body.appendChild(s),s.click(),URL.revokeObjectURL(s.href),document.body.removeChild(s)}else navigator.msSaveBlob(n,i);a.loadingDownloadBtn=0})}}},u=c,g=a("17cc"),h=Object(g["a"])(u,n,o,!1,null,null,null);t["default"]=h.exports}}]);