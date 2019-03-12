/* -----------------------------------------------------------*/
/* Description:备份的数据表[结构]：status_maps,system_configs,tables*/
/* Description:备份的数据表[数据]：status_maps,system_configs,tables*/
/* Time: 2019-03-12 15:35:32*/
/* -----------------------------------------------------------*/
/* SQLFile Label：#1*/
/* -----------------------------------------------------------*/


/* 表的结构 status_maps*/ 
DROP TABLE IF EXISTS status_maps;
CREATE TABLE `status_maps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '表名称',
  `column` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '字段名称',
  `status_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '状态码',
  `status_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '状态码说明',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status_maps_table_name_index` (`table_name`),
  KEY `status_maps_column_index` (`column`),
  KEY `status_maps_status_code_index` (`status_code`),
  KEY `status_maps_status_description_index` (`status_description`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 system_configs*/ 
DROP TABLE IF EXISTS system_configs;
CREATE TABLE `system_configs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `flag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置英文标识',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置标题',
  `config_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'basic' COMMENT '配置分组',
  `value` varchar(600) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置值',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置描述',
  `enable` enum('T','F') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'T',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `system_configs_flag_index` (`flag`),
  KEY `system_configs_enable_index` (`enable`),
  KEY `system_configs_config_group_index` (`config_group`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 tables*/ 
DROP TABLE IF EXISTS tables;
CREATE TABLE `tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '表名称',
  `table_name_cn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '表中文名称',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tables_table_name_index` (`table_name`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/* 转存表中的数据:status_maps*/ 
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('1','users','enable','T','启用','是否启用');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('2','users','enable','F','禁用','是否启用');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('3','users','is_admin','T','是','是否为后台管理员：可登录');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('4','users','is_admin','F','否','是否为后台管理员：不可登录');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('5','logs','type','insert','新增','新增数据');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('6','logs','type','update','修改','修改数据');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('7','logs','type','destroy','删除','删除数据');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`updated_at`) VALUES ('8','logs','type','error','错误日志','错误日志记录','2019-02-20 10:02:01');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`deleted_at`,`created_at`,`updated_at`) VALUES ('10','users','enable','S','差评','','2019-02-20 10:32:45','2019-02-20 10:32:25','2019-02-20 10:32:45');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('11','attachments','file_type','file','文件','','2019-02-23 10:55:02','2019-02-23 10:55:02');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('12','attachments','file_type','pic','图片','','2019-02-23 10:55:10','2019-02-23 10:55:10');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('13','attachments','file_type','video','视频','','2019-02-23 10:55:19','2019-02-23 10:55:19');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('14','system_configs','enable','T','启用','','2019-02-26 10:09:03','2019-02-26 10:09:21');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('15','system_configs','enable','F','禁用','','2019-02-26 10:09:10','2019-02-26 10:09:10');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('16','attachments','category','lucmsee','系统附件','','2019-02-26 14:46:05','2019-02-26 14:46:05');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('17','article_categories','enable','T','启用','','2019-02-27 17:21:39','2019-02-27 17:21:39');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('18','article_categories','enable','F','禁用','','2019-02-27 17:21:48','2019-02-27 17:21:48');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('19','articles','enable','T','启用','','2019-03-07 11:48:34','2019-03-07 11:48:34');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('20','articles','enable','F','禁用','','2019-03-07 11:48:40','2019-03-07 11:48:40');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('21','articles','recommend','T','是','','2019-03-07 11:48:55','2019-03-07 11:48:55');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('22','articles','recommend','F','否','','2019-03-07 11:49:05','2019-03-07 11:49:05');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('23','articles','top','T','是','','2019-03-07 11:49:17','2019-03-07 11:49:17');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('24','articles','top','F','否','','2019-03-07 11:49:27','2019-03-07 11:49:27');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('25','articles','access_type','pub','公开','','2019-03-07 11:50:04','2019-03-07 11:50:25');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('26','articles','access_type','pri','私密','','2019-03-07 11:50:33','2019-03-07 11:50:33');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('27','articles','access_type','pwd','密码访问','','2019-03-07 11:50:43','2019-03-07 11:50:43');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('28','attachments','category','editor_article_content','富文本文章内容','','2019-03-11 14:21:00','2019-03-11 14:21:00');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('29','attachments','category','article_cover_img','文章封面','','2019-03-11 14:21:13','2019-03-11 14:21:13');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('30','attachments','category','avatar','用户头像','','2019-03-11 14:22:06','2019-03-11 14:22:06');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('31','attachments','category','markdown_editor_article_content','markdown富文本编辑器文章内容','','2019-03-12 10:14:37','2019-03-12 10:14:37');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('32','admin_messages','message_type','system','系统消息','','2019-03-12 15:30:06','2019-03-12 15:30:06');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('33','admin_messages','message_type','suggest','意建反馈','','2019-03-12 15:30:29','2019-03-12 15:30:29');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('34','admin_messages','is_read','T','已读','是否已读','2019-03-12 15:30:54','2019-03-12 15:30:54');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('35','admin_messages','is_read','F','未读','','2019-03-12 15:31:02','2019-03-12 15:31:02');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('36','app_messages','message_type','system','系统消息','','2019-03-12 15:31:59','2019-03-12 15:31:59');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('37','app_messages','is_read','T','已读','','2019-03-12 15:32:09','2019-03-12 15:32:09');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('38','app_messages','is_read','F','未读','','2019-03-12 15:32:19','2019-03-12 15:32:19');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('39','app_messages','is_alert_at_home','T','是','是否在首页提示','2019-03-12 15:32:43','2019-03-12 15:32:43');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`,`created_at`,`updated_at`) VALUES ('40','app_messages','is_alert_at_home','F','否','是否在首页提示','2019-03-12 15:32:55','2019-03-12 15:32:55');


/* 转存表中的数据:system_configs*/ 
INSERT INTO `system_configs` (`id`,`flag`,`title`,`config_group`,`value`,`description`,`enable`,`created_at`,`updated_at`) VALUES ('2','max_bak_sql_file_size','数据库备份每个文件最大字节(单位为M)','basic','20','','T','2019-02-26 11:03:04','2019-02-26 11:03:04');


/* 转存表中的数据:tables*/ 
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`,`updated_at`) VALUES ('1','tables','所有表管理','所有表名都记录到此表中了','2019-02-21 17:52:29');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`,`updated_at`) VALUES ('2','status_maps','表状态管理','表状态数据字典','2019-02-19 11:24:50');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('3','users','用户表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('4','logs','日志表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('5','permissions','权限表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('6','model_has_permissions','权限关联表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('7','roles','角色表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('8','model_has_roles','角色关联表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('9','attachments','附件表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`,`created_at`,`updated_at`) VALUES ('19','system_configs','系统配置表','','2019-02-26 10:08:51','2019-02-26 10:08:51');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`,`created_at`,`updated_at`) VALUES ('20','article_categories','文章分类表','','2019-02-27 17:21:26','2019-02-27 17:21:26');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`,`created_at`,`updated_at`) VALUES ('21','articles','文章表','','2019-03-07 11:48:21','2019-03-07 11:48:21');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`,`created_at`,`updated_at`) VALUES ('22','admin_messages','后台消息表','','2019-03-12 15:29:38','2019-03-12 15:29:38');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`,`created_at`,`updated_at`) VALUES ('23','app_messages','app 消息表','','2019-03-12 15:31:31','2019-03-12 15:31:31');
