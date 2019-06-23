
/**
*@tables 系统中的表
*@status_maps 数据字典表
*@users 用户表
*@logs 日志表
*@permissions  权限表
*@roles  角色表
*@model_has_permissions  用户多权限表
*@model_has_roles  用户多角色表
*@role_has_permissions  角色多权限表
*@attachments  附件表
*@table_bak_records  数据库备份记录表
*@system_configs  系统配置表
*@article_categories  文章分类表
*@tags  标签表
*@articles  文章表
*@model_has_tags  多标签表
*@admin_messages  后台消息表
*@app_messages  app消息表
*@carousels  轮播图表
*@smses  短信记录表
*@app_versions  app 版本更新记录表
*@user_agreements  用户协议表
*@migrations  laravel 数据库迁移记录表
*@oauth_access_tokens
*@oauth_auth_codes
*@oauth_clients
*@oauth_personal_access_clients
*@oauth_refresh_tokens
 */

/* 表的结构 tables*/
drop table if exists tables;
create table `tables` (
  `id` int(10) unsigned not null auto_increment,
  `table_name` varchar(255) not null default '' comment '表名称',
  `table_name_cn` varchar(255) not null default '' comment '表中文名称',
  `remark` varchar(255)  not null default '' comment '备注',
  `deleted_at` timestamp null default null,
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  primary key (`id`),
  key `tables_table_name_index` (`table_name`)
) engine=innodb auto_increment=1 default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 status_maps*/
drop table if exists status_maps;
create table `status_maps` (
  `id` int(10) unsigned not null auto_increment,
  `table_name` varchar(255)  not null default '' comment '表名称',
  `column` varchar(255)  not null default '' comment '字段名称',
  `status_code` varchar(255)  not null default '' comment '状态码',
  `status_description` varchar(255)  not null default '' comment '状态码说明',
  `remark` varchar(255)  not null default '' comment '备注',
  `deleted_at` timestamp null default null,
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  primary key (`id`),
  key `status_maps_table_name_index` (`table_name`),
  key `status_maps_column_index` (`column`),
  key `status_maps_status_code_index` (`status_code`),
  key `status_maps_status_description_index` (`status_description`)
) engine=innodb auto_increment=1 default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 users*/
drop table if exists users;
create table `users` (
  `id` bigint(20) unsigned not null auto_increment,
  `real_name` varchar(255)  not null default '',
  `nickname` varchar(255)  not null default '',
  `avatar` varchar(255)  not null default '' comment '头像',
  `mobile` varchar(255)  not null default '',
  `email` varchar(255)  not null default '',
  `password` varchar(255)  not null default '',
  `enable` enum('t','f')  not null default 'f' comment '启用状态：f禁用，t启用',
  `is_admin` enum('t','f')  not null default 'f' comment '是否可登录后台：f否，是',
  `description` varchar(255)  not null default '' comment '一句话描述',
  `remark` varchar(255)  not null default '' comment '备注',
  `remember_token` varchar(255)  not null default '',
  `last_login_at` timestamp null default null,
  `deleted_at` timestamp null default null,
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  primary key (`id`),
  key `users_mobile_index` (`mobile`),
  key `users_enable_index` (`enable`),
  key `users_is_admin_index` (`is_admin`)
) engine=innodb auto_increment=1 default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 logs*/
drop table if exists logs;
create table `logs` (
  `id` bigint(20) unsigned not null auto_increment,
  `user_id` int(11) not null default '0',
  `table_name` varchar(255)  not null default '' comment '表名',
  `type` varchar(255)  not null default 'insert' comment '类型',
  `ip` varchar(45)  not null default '' comment 'ip',
  `description` varchar(255)  not null default '' comment '说明',
  `content` text  not null comment '日志内容,json_encode(相关数据)',
  `deleted_at` timestamp null default null,
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  primary key (`id`),
  key `logs_user_id_index` (`user_id`),
  key `logs_table_name_index` (`table_name`),
  key `logs_type_index` (`type`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 permissions*/
drop table if exists permissions;
create table `permissions` (
  `id` int(10) unsigned not null auto_increment,
  `name` varchar(255)  not null,
  `guard_name` varchar(255)  not null,
  `description` varchar(255)  not null default '' comment '说明',
  `remark` varchar(255)  not null default '',
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  primary key (`id`)
) engine=innodb auto_increment=1 default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 roles*/
drop table if exists roles;
create table `roles` (
  `id` int(10) unsigned not null auto_increment,
  `name` varchar(255)  not null,
  `guard_name` varchar(255)  not null,
  `description` varchar(255)  not null default '' comment '说明',
  `remark` varchar(255)  not null default '',
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  primary key (`id`)
) engine=innodb auto_increment=1 default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 model_has_permissions*/
drop table if exists model_has_permissions;
create table `model_has_permissions` (
  `permission_id` int(10) unsigned not null,
  `model_type` varchar(255)  not null,
  `model_id` bigint(20) unsigned not null,
  primary key (`permission_id`,`model_id`,`model_type`),
  key `model_has_permissions_model_type_model_id_index` (`model_type`,`model_id`),
  constraint `model_has_permissions_permission_id_foreign` foreign key (`permission_id`) references `permissions` (`id`) on delete cascade
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 model_has_roles*/
drop table if exists model_has_roles;
create table `model_has_roles` (
  `role_id` int(10) unsigned not null,
  `model_type` varchar(255)  not null,
  `model_id` bigint(20) unsigned not null,
  primary key (`role_id`,`model_id`,`model_type`),
  key `model_has_roles_model_type_model_id_index` (`model_type`,`model_id`),
  constraint `model_has_roles_role_id_foreign` foreign key (`role_id`) references `roles` (`id`) on delete cascade
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 role_has_permissions*/
drop table if exists role_has_permissions;
create table `role_has_permissions` (
  `permission_id` int(10) unsigned not null,
  `role_id` int(10) unsigned not null,
  primary key (`permission_id`,`role_id`),
  key `role_has_permissions_role_id_foreign` (`role_id`),
  constraint `role_has_permissions_permission_id_foreign` foreign key (`permission_id`) references `permissions` (`id`) on delete cascade,
  constraint `role_has_permissions_role_id_foreign` foreign key (`role_id`) references `roles` (`id`) on delete cascade
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 attachments*/
drop table if exists attachments;
create table `attachments` (
  `id` bigint(20) unsigned not null auto_increment,
  `user_id` int(11) not null default '0' comment '上传用户 id',
  `ip` varchar(45)  not null default '' comment '附件上传者 ip',
  `original_name` varchar(255)  not null default '' comment '原始名称',
  `mime_type` varchar(255)  not null default '' comment 'mime 类型',
  `file_type` enum('pic','file','video')  not null default 'pic' comment '类型',
  `size` varchar(255)  not null default '0' comment '大小/kb',
  `category` varchar(255)  not null default 'tmp' comment '归类',
  `domain` varchar(255)  not null default '' comment '域名地址,https://jiayouhaoshi.com',
  `storage_path` varchar(255)  not null default '' comment '附件相对 storage 目录,app/public/images/avatars',
  `link_path` varchar(255)  not null default '' comment '附件相对网站根目录,访问路径：storage/images/avatars',
  `storage_name` varchar(255)  not null default '' comment '存储名称',
  `remark` varchar(255)  not null default '' comment '附件备注',
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  `deleted_at` timestamp null default null,
  primary key (`id`),
  key `attachments_user_id_index` (`user_id`),
  key `attachments_file_type_index` (`file_type`),
  key `attachments_category_index` (`category`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 table_bak_records*/
drop table if exists table_bak_records;
create table `table_bak_records` (
  `id` int(10) unsigned not null auto_increment,
  `bak_tables_name` text  not null comment '备份的表名称，多个以逗号隔开;users,logs',
  `user_id` int(11) not null default '0',
  `file_num` tinyint(4) not null default '1' comment '产生文件数量',
  `files` text  not null comment 'json_encode([/data/wwwroot/lucmsee=2019-01-01=11:53:54=abc=1.sql,/data/wwwroot/lucmsee=2019-01-01=11:53:54=abc=2.sql])',
  `file_size` int(11) not null default '0' comment '单位为字节',
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  `deleted_at` timestamp null default null,
  primary key (`id`),
  key `table_bak_records_user_id_index` (`user_id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 system_configs*/
drop table if exists system_configs;
create table `system_configs` (
  `id` int(10) unsigned not null auto_increment,
  `flag` varchar(255)  not null default '' comment '配置英文标识',
  `title` varchar(255)  not null default '' comment '配置标题',
  `config_group` varchar(255)  not null default 'basic' comment '配置分组',
  `value` varchar(600)  not null default '' comment '配置值',
  `description` varchar(255)  not null default '' comment '配置描述',
  `enable` enum('t','f')  not null default 't',
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  primary key (`id`),
  key `system_configs_flag_index` (`flag`),
  key `system_configs_enable_index` (`enable`),
  key `system_configs_config_group_index` (`config_group`)
) engine=innodb auto_increment=1 default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 article_categories*/
drop table if exists article_categories;
create table `article_categories` (
  `id` int(10) unsigned not null auto_increment,
  `name` varchar(255)  not null default '' comment '分类名称',
  `pid` int(11) not null default '0' comment '上级 id',
  `enable` enum('t','f')  not null default 'f' comment '是否启用',
  `weight` int(11) not null default '50',
  `description` varchar(255)  not null default '' comment '描述',
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  primary key (`id`),
  key `article_categories_pid_index` (`pid`),
  key `article_categories_weight_index` (`weight`),
  key `article_categories_enable_index` (`enable`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 tags*/
drop table if exists tags;
create table `tags` (
  `id` int(10) unsigned not null auto_increment,
  `name` varchar(255)  not null default '' comment '名称',
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  primary key (`id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 articles*/
drop table if exists articles;
create table `articles` (
  `id` bigint(20) unsigned not null auto_increment,
  `title` varchar(255)  not null default '' comment '文章标题',
  `slug` varchar(255)  not null default '' comment 'slug',
  `keywords` varchar(255)  not null default '' comment '关键词,以英文逗号隔开',
  `description` varchar(255)  not null default '' comment '描述',
  `cover_image` varchar(255)  not null default '' comment '封面图片',
  `content` text  not null comment '内容',
  `user_id` int(11) not null default '0' comment '作者 id',
  `article_category_id` int(11) not null default '0' comment '分类 id',
  `view_count` int(10) unsigned not null default '0' comment '查看数量',
  `vote_count` int(10) unsigned not null default '0' comment '点赞数量',
  `comment_count` int(10) unsigned not null default '0' comment '评论数量',
  `collection_count` int(10) unsigned not null default '0' comment '收藏数量',
  `enable` enum('t','f')  not null default 'f' comment '启用状态：f禁用，t启用',
  `recommend` enum('t','f')  not null default 'f' comment '是否推荐到首页',
  `top` enum('t','f')  not null default 'f' comment '是否置顶',
  `weight` int(11) not null default '20' comment '权重',
  `access_type` enum('pub','pri','pwd')  not null default 'pub' comment '访问权限类型：公开、私密、密码访问',
  `access_value` varchar(255)  not null default '' comment '访问权限值：pub->不公开的用户ids 逗号隔开,为空则所有人都能访问,pri->公开的用户ids 逗号隔开，为空则只有作者能访问,pwd->访问密码，aes-ecb-128加密方式加密',
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  primary key (`id`),
  key `articles_weight_index` (`weight`),
  key `articles_article_category_id_index` (`article_category_id`),
  key `articles_user_id_index` (`user_id`),
  key `articles_access_type_index` (`access_type`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 model_has_tags*/
drop table if exists model_has_tags;
create table `model_has_tags` (
  `tag_id` bigint(20) not null,
  `model_type` varchar(255)  not null,
  `model_id` bigint(20) unsigned not null,
  key `model_has_tags_model_type_model_id_index` (`model_type`,`model_id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 admin_messages*/
drop table if exists admin_messages;
create table `admin_messages` (
  `id` bigint(20) unsigned not null auto_increment,
  `user_id` int(11) not null default '0' comment '哪个用户发的消息，对应 app 用户表',
  `admin_id` int(11) not null default '0' comment '发给哪个管理员的消息,0为所有管理员',
  `title` varchar(255)  not null default '',
  `message_type` varchar(255)  not null default 'system' comment '消息类型',
  `content` varchar(2000)  not null default '',
  `is_read` enum('t','f')  not null default 'f' comment '是否已读',
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  `deleted_at` timestamp null default null,
  primary key (`id`),
  key `admin_messages_message_type_index` (`message_type`),
  key `admin_messages_is_read_index` (`is_read`),
  key `admin_messages_user_id_index` (`user_id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 app_messages*/
drop table if exists app_messages;
create table `app_messages` (
  `id` bigint(20) unsigned not null auto_increment,
  `admin_id` int(11) not null default '0' comment '哪个管理员发的消息',
  `user_id` int(11) not null default '0' comment '发给哪个用户的消息,0为所有管理员',
  `title` varchar(255)  not null default '',
  `content` varchar(255)  not null default '',
  `url` varchar(255)  not null default '' comment '跳转url',
  `message_type` varchar(255)  not null default 'system' comment '消息类型',
  `is_read` enum('t','f')  not null default 'f' comment '是否已读',
  `is_alert_at_home` enum('f','t')  not null default 'f' comment '是否在首页弹出提示框，已读后就不再弹出',
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  `deleted_at` timestamp null default null,
  primary key (`id`),
  key `app_messages_message_type_index` (`message_type`),
  key `app_messages_is_read_index` (`is_read`),
  key `app_messages_user_id_index` (`user_id`),
  key `app_messages_is_alert_at_home_index` (`is_alert_at_home`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;



/* 表的结构 carousels*/
drop table if exists carousels;
create table `carousels` (
  `id` int(10) unsigned not null auto_increment,
  `title` varchar(255)  not null default '',
  `cover_image` varchar(255)  not null default '',
  `description` varchar(255)  not null default '',
  `url` varchar(255)  not null default '',
  `weight` int(11) not null default '10',
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  primary key (`id`),
  key `carousels_weight_index` (`weight`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 smses*/
drop table if exists smses;
create table `smses` (
  `id` bigint(20) unsigned not null auto_increment,
  `mobile` varchar(255)  not null,
  `type` varchar(255)  not null default 'test',
  `code` varchar(255)  not null,
  `ip` varchar(45)  not null,
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  primary key (`id`),
  key `smses_mobile_index` (`mobile`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 app_versions*/
drop table if exists app_versions;
create table `app_versions` (
  `id` int(10) unsigned not null auto_increment,
  `name` enum('lucmsee')  not null default 'lucmsee' comment 'app名称',
  `mobile_os` enum('android','ios','all')  not null default 'all',
  `version_sn` varchar(255)  not null default '1.0.0',
  `description` varchar(1000)  not null default '',
  `package_url` varchar(255)  not null default '' comment '包地址',
  `is_force_update` enum('t','f')  not null default 'f' comment '是否强制更新',
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  primary key (`id`),
  key `app_versions_version_sn_index` (`version_sn`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 user_agreements*/
drop table if exists user_agreements;
create table `user_agreements` (
  `id` bigint(20) unsigned not null auto_increment,
  `agree_type` varchar(255)  not null comment '协议类型',
  `name` varchar(255)  not null default '' comment '协议名称',
  `description` varchar(255)  not null default '' comment '协议描述',
  `content` longtext  not null comment '协议内容',
  `user_id` int(11) not null default '0' comment '操作人',
  `enable` enum('t','f')  not null default 'f' comment '是否启用',
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  `deleted_at` timestamp null default null,
  primary key (`id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 migrations*/
drop table if exists migrations;
create table `migrations` (
  `id` int(10) unsigned not null auto_increment,
  `migration` varchar(255)  not null,
  `batch` int(11) not null,
  primary key (`id`)
) engine=innodb auto_increment=1 default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 oauth_access_tokens*/
drop table if exists oauth_access_tokens;
create table `oauth_access_tokens` (
  `id` varchar(100)  not null,
  `user_id` int(11) default null,
  `client_id` int(10) unsigned not null,
  `name` varchar(255)  default null,
  `scopes` text ,
  `revoked` tinyint(1) not null,
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  `expires_at` datetime default null,
  primary key (`id`),
  key `oauth_access_tokens_user_id_index` (`user_id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 oauth_auth_codes*/
drop table if exists oauth_auth_codes;
create table `oauth_auth_codes` (
  `id` varchar(100)  not null,
  `user_id` int(11) not null,
  `client_id` int(10) unsigned not null,
  `scopes` text ,
  `revoked` tinyint(1) not null,
  `expires_at` datetime default null,
  primary key (`id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 oauth_clients*/
drop table if exists oauth_clients;
create table `oauth_clients` (
  `id` int(10) unsigned not null auto_increment,
  `user_id` int(11) default null,
  `name` varchar(255)  not null,
  `secret` varchar(100)  not null,
  `redirect` text  not null,
  `personal_access_client` tinyint(1) not null,
  `password_client` tinyint(1) not null,
  `revoked` tinyint(1) not null,
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  primary key (`id`),
  key `oauth_clients_user_id_index` (`user_id`)
) engine=innodb auto_increment=1 default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 oauth_personal_access_clients*/
drop table if exists oauth_personal_access_clients;
create table `oauth_personal_access_clients` (
  `id` int(10) unsigned not null auto_increment,
  `client_id` int(10) unsigned not null,
  `created_at` timestamp null default null,
  `updated_at` timestamp null default null,
  primary key (`id`),
  key `oauth_personal_access_clients_client_id_index` (`client_id`)
) engine=innodb auto_increment=1 default charset=utf8mb4 collate=utf8mb4_unicode_ci;

/* 表的结构 oauth_refresh_tokens*/
drop table if exists oauth_refresh_tokens;
create table `oauth_refresh_tokens` (
  `id` varchar(100)  not null,
  `access_token_id` varchar(100)  not null,
  `revoked` tinyint(1) not null,
  `expires_at` datetime default null,
  primary key (`id`),
  key `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;

