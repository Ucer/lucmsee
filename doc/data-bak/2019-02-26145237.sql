/* -----------------------------------------------------------*/
/* Description:备份的数据表[结构]：status_maps,system_configs,tables*/
/* Description:备份的数据表[数据]：status_maps,system_configs,tables*/
/* Time: 2019-02-26 14:52:20*/
/* -----------------------------------------------------------*/
/* SQLFile Label：#1*/
/* -----------------------------------------------------------*/


/* 表的结构 status_maps*/ 
DROP TABLE IF EXISTS status_maps;
CREATE TABLE status_maps (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '表名称',
  `column` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '字段名称',
  status_code varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '状态码',
  status_description varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '状态码说明',
  remark varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  deleted_at timestamp NULL DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY status_maps_table_name_index (`table_name`),
  KEY status_maps_column_index (`column`),
  KEY status_maps_status_code_index (status_code),
  KEY status_maps_status_description_index (status_description)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 system_configs*/ 
DROP TABLE IF EXISTS system_configs;
CREATE TABLE system_configs (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  flag varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置英文标识',
  title varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置标题',
  config_group varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'basic' COMMENT '配置分组',
  `value` varchar(600) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置值',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置描述',
  `enable` enum('T','F') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'T',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY system_configs_flag_index (flag),
  KEY system_configs_enable_index (`enable`),
  KEY system_configs_config_group_index (config_group)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 tables*/ 
DROP TABLE IF EXISTS tables;
CREATE TABLE `tables` (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '表名称',
  table_name_cn varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '表中文名称',
  remark varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  deleted_at timestamp NULL DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY tables_table_name_index (`table_name`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/* 转存表中的数据:status_maps*/ 
INSERT INTO `status_maps`(id,table_name,column,status_code,status_description,remark) VALUES ('1','users','enable','T','启用','是否启用');
INSERT INTO `status_maps`(id,table_name,column,status_code,status_description,remark) VALUES ('2','users','enable','F','禁用','是否启用');
INSERT INTO `status_maps`(id,table_name,column,status_code,status_description,remark) VALUES ('3','users','is_admin','T','是','是否为后台管理员：可登录');
INSERT INTO `status_maps`(id,table_name,column,status_code,status_description,remark) VALUES ('4','users','is_admin','F','否','是否为后台管理员：不可登录');
INSERT INTO `status_maps`(id,table_name,column,status_code,status_description,remark) VALUES ('5','logs','type','insert','新增','新增数据');
INSERT INTO `status_maps`(id,table_name,column,status_code,status_description,remark) VALUES ('6','logs','type','update','修改','修改数据');
INSERT INTO `status_maps`(id,table_name,column,status_code,status_description,remark) VALUES ('7','logs','type','destroy','删除','删除数据');
INSERT INTO `status_maps`(id,table_name,column,status_code,status_description,remark,updated_at) VALUES ('8','logs','type','error','错误日志','错误日志记录','2019-02-20 10:02:01');
INSERT INTO `status_maps`(id,table_name,column,status_code,status_description,remark,deleted_at,created_at,updated_at) VALUES ('10','users','enable','S','差评','','2019-02-20 10:32:45','2019-02-20 10:32:25','2019-02-20 10:32:45');
INSERT INTO `status_maps`(id,table_name,column,status_code,status_description,remark,created_at,updated_at) VALUES ('11','attachments','file_type','file','文件','','2019-02-23 10:55:02','2019-02-23 10:55:02');
INSERT INTO `status_maps`(id,table_name,column,status_code,status_description,remark,created_at,updated_at) VALUES ('12','attachments','file_type','pic','图片','','2019-02-23 10:55:10','2019-02-23 10:55:10');
INSERT INTO `status_maps`(id,table_name,column,status_code,status_description,remark,created_at,updated_at) VALUES ('13','attachments','file_type','video','视频','','2019-02-23 10:55:19','2019-02-23 10:55:19');
INSERT INTO `status_maps`(id,table_name,column,status_code,status_description,remark,created_at,updated_at) VALUES ('14','system_configs','enable','T','启用','','2019-02-26 10:09:03','2019-02-26 10:09:21');
INSERT INTO `status_maps`(id,table_name,column,status_code,status_description,remark,created_at,updated_at) VALUES ('15','system_configs','enable','F','禁用','','2019-02-26 10:09:10','2019-02-26 10:09:10');
INSERT INTO `status_maps`(id,table_name,column,status_code,status_description,remark,created_at,updated_at) VALUES ('16','attachments','category','lucmsee','系统附件','','2019-02-26 14:46:05','2019-02-26 14:46:05');


/* 转存表中的数据:system_configs*/ 
INSERT INTO `system_configs`(id,flag,title,config_group,value,description,enable,created_at,updated_at) VALUES ('2','max_bak_sql_file_size','数据库备份每个文件最大字节(单位为M)','basic','20','','T','2019-02-26 11:03:04','2019-02-26 11:03:04');


/* 转存表中的数据:tables*/ 
INSERT INTO `tables`(id,table_name,table_name_cn,remark,updated_at) VALUES ('1','tables','所有表管理','所有表名都记录到此表中了','2019-02-21 17:52:29');
INSERT INTO `tables`(id,table_name,table_name_cn,remark,updated_at) VALUES ('2','status_maps','表状态管理','表状态数据字典','2019-02-19 11:24:50');
INSERT INTO `tables`(id,table_name,table_name_cn,remark) VALUES ('3','users','用户表','');
INSERT INTO `tables`(id,table_name,table_name_cn,remark) VALUES ('4','logs','日志表','');
INSERT INTO `tables`(id,table_name,table_name_cn,remark) VALUES ('5','permissions','权限表','');
INSERT INTO `tables`(id,table_name,table_name_cn,remark) VALUES ('6','model_has_permissions','权限关联表','');
INSERT INTO `tables`(id,table_name,table_name_cn,remark) VALUES ('7','roles','角色表','');
INSERT INTO `tables`(id,table_name,table_name_cn,remark) VALUES ('8','model_has_roles','角色关联表','');
INSERT INTO `tables`(id,table_name,table_name_cn,remark) VALUES ('9','attachments','附件表','');
INSERT INTO `tables`(id,table_name,table_name_cn,remark,created_at,updated_at) VALUES ('19','system_configs','系统配置表','','2019-02-26 10:08:51','2019-02-26 10:08:51');
