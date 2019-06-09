/* -----------------------------------------------------------*/
/* Description:备份的数据表[结构]：admin_messages,app_messages,app_versions,article_categories,articles,attachments,carousels,logs,migrations,model_has_permissions,model_has_roles,model_has_tags,oauth_access_tokens,oauth_auth_codes,oauth_clients,oauth_personal_access_clients,oauth_refresh_tokens,permissions,role_has_permissions,roles,smses,status_maps,system_configs,table_bak_records,tables,tags,user_agreements,users*/
/* Description:备份的数据表[数据]：admin_messages,app_messages,app_versions,article_categories,articles,attachments,carousels,logs,migrations,model_has_permissions,model_has_roles,model_has_tags,oauth_access_tokens,oauth_auth_codes,oauth_clients,oauth_personal_access_clients,oauth_refresh_tokens,permissions,role_has_permissions,roles,smses,status_maps,system_configs,table_bak_records,tables,tags,user_agreements,users*/
/* Time: 2019-06-09 21:32:44*/
/* -----------------------------------------------------------*/
/* SQLFile Label：#1*/
/* -----------------------------------------------------------*/


/* 表的结构 admin_messages*/ 
DROP TABLE IF EXISTS admin_messages;
CREATE TABLE `admin_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '哪个用户发的消息，对应 app 用户表',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '发给哪个管理员的消息,0为所有管理员',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `message_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'system' COMMENT '消息类型',
  `content` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `is_read` enum('T','F') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F' COMMENT '是否已读',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_messages_message_type_index` (`message_type`),
  KEY `admin_messages_is_read_index` (`is_read`),
  KEY `admin_messages_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 app_messages*/ 
DROP TABLE IF EXISTS app_messages;
CREATE TABLE `app_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '哪个管理员发的消息',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '发给哪个用户的消息,0为所有管理员',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '跳转url',
  `message_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'system' COMMENT '消息类型',
  `is_read` enum('T','F') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F' COMMENT '是否已读',
  `is_alert_at_home` enum('F','T') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F' COMMENT '是否在首页弹出提示框，已读后就不再弹出',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_messages_message_type_index` (`message_type`),
  KEY `app_messages_is_read_index` (`is_read`),
  KEY `app_messages_user_id_index` (`user_id`),
  KEY `app_messages_is_alert_at_home_index` (`is_alert_at_home`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 app_versions*/ 
DROP TABLE IF EXISTS app_versions;
CREATE TABLE `app_versions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` enum('lucmsee') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lucmsee' COMMENT 'app名称',
  `mobile_os` enum('android','ios','all') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `version_sn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1.0.0',
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `package_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '包地址',
  `is_force_update` enum('T','F') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F' COMMENT '是否强制更新',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_versions_version_sn_index` (`version_sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 article_categories*/ 
DROP TABLE IF EXISTS article_categories;
CREATE TABLE `article_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级 id',
  `enable` enum('T','F') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F' COMMENT '是否启用',
  `weight` int(11) NOT NULL DEFAULT '50',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article_categories_pid_index` (`pid`),
  KEY `article_categories_weight_index` (`weight`),
  KEY `article_categories_enable_index` (`enable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 articles*/ 
DROP TABLE IF EXISTS articles;
CREATE TABLE `articles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文章标题',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'slug',
  `keywords` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关键词,以英文逗号隔开',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '封面图片',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '作者 id',
  `article_category_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类 id',
  `view_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看数量',
  `vote_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数量',
  `comment_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数量',
  `collection_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数量',
  `enable` enum('T','F') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F' COMMENT '启用状态：F禁用，T启用',
  `recommend` enum('T','F') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F' COMMENT '是否推荐到首页',
  `top` enum('T','F') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F' COMMENT '是否置顶',
  `weight` int(11) NOT NULL DEFAULT '20' COMMENT '权重',
  `access_type` enum('pub','pri','pwd') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pub' COMMENT '访问权限类型：公开、私密、密码访问',
  `access_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '访问权限值：pub->不公开的用户ids 逗号隔开,为空则所有人都能访问,pri->公开的用户ids 逗号隔开，为空则只有作者能访问,pwd->访问密码，aes-ecb-128加密方式加密',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `articles_weight_index` (`weight`),
  KEY `articles_article_category_id_index` (`article_category_id`),
  KEY `articles_user_id_index` (`user_id`),
  KEY `articles_access_type_index` (`access_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 attachments*/ 
DROP TABLE IF EXISTS attachments;
CREATE TABLE `attachments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '上传用户 id',
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '附件上传者 ip',
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '原始名称',
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'mime 类型',
  `file_type` enum('pic','file','video') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pic' COMMENT '类型',
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '大小/kb',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tmp' COMMENT '归类',
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '域名地址,https://jiayouhaoshi.com',
  `storage_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '附件相对 storage 目录,app/public/images/avatars',
  `link_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '附件相对网站根目录,访问路径：storage/images/avatars',
  `storage_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '存储名称',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '附件备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attachments_user_id_index` (`user_id`),
  KEY `attachments_file_type_index` (`file_type`),
  KEY `attachments_category_index` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 carousels*/ 
DROP TABLE IF EXISTS carousels;
CREATE TABLE `carousels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `weight` int(11) NOT NULL DEFAULT '10',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carousels_weight_index` (`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 logs*/ 
DROP TABLE IF EXISTS logs;
CREATE TABLE `logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '表名',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'insert' COMMENT '类型',
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'IP',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '说明',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '日志内容,json_encode(相关数据)',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logs_user_id_index` (`user_id`),
  KEY `logs_table_name_index` (`table_name`),
  KEY `logs_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 migrations*/ 
DROP TABLE IF EXISTS migrations;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 model_has_permissions*/ 
DROP TABLE IF EXISTS model_has_permissions;
CREATE TABLE `model_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_type_model_id_index` (`model_type`,`model_id`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 model_has_roles*/ 
DROP TABLE IF EXISTS model_has_roles;
CREATE TABLE `model_has_roles` (
  `role_id` int(10) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_type_model_id_index` (`model_type`,`model_id`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 model_has_tags*/ 
DROP TABLE IF EXISTS model_has_tags;
CREATE TABLE `model_has_tags` (
  `tag_id` bigint(20) NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  KEY `model_has_tags_model_type_model_id_index` (`model_type`,`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 oauth_access_tokens*/ 
DROP TABLE IF EXISTS oauth_access_tokens;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 oauth_auth_codes*/ 
DROP TABLE IF EXISTS oauth_auth_codes;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 oauth_clients*/ 
DROP TABLE IF EXISTS oauth_clients;
CREATE TABLE `oauth_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 oauth_personal_access_clients*/ 
DROP TABLE IF EXISTS oauth_personal_access_clients;
CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 oauth_refresh_tokens*/ 
DROP TABLE IF EXISTS oauth_refresh_tokens;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 permissions*/ 
DROP TABLE IF EXISTS permissions;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '说明',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 role_has_permissions*/ 
DROP TABLE IF EXISTS role_has_permissions;
CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 roles*/ 
DROP TABLE IF EXISTS roles;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '说明',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 smses*/ 
DROP TABLE IF EXISTS smses;
CREATE TABLE `smses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'test',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `smses_mobile_index` (`mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 table_bak_records*/ 
DROP TABLE IF EXISTS table_bak_records;
CREATE TABLE `table_bak_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bak_tables_name` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '备份的表名称，多个以逗号隔开;users,logs',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `file_num` tinyint(4) NOT NULL DEFAULT '1' COMMENT '产生文件数量',
  `files` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'json_encode([/data/wwwroot/lucmsee=2019-01-01=11:53:54=ABC=1.sql,/data/wwwroot/lucmsee=2019-01-01=11:53:54=ABC=2.sql])',
  `file_size` int(11) NOT NULL DEFAULT '0' COMMENT '单位为字节',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `table_bak_records_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 tags*/ 
DROP TABLE IF EXISTS tags;
CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 user_agreements*/ 
DROP TABLE IF EXISTS user_agreements;
CREATE TABLE `user_agreements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `agree_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '协议类型',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '协议名称',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '协议描述',
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '协议内容',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作人',
  `enable` enum('T','F') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F' COMMENT '是否启用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* 表的结构 users*/ 
DROP TABLE IF EXISTS users;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `real_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `enable` enum('T','F') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F' COMMENT '启用状态：F禁用，T启用',
  `is_admin` enum('T','F') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F' COMMENT '是否可登录后台：F否，是',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '一句话描述',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_mobile_index` (`mobile`),
  KEY `users_enable_index` (`enable`),
  KEY `users_is_admin_index` (`is_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/* 转存表中的数据:admin_messages*/ 


/* 转存表中的数据:app_messages*/ 


/* 转存表中的数据:app_versions*/ 


/* 转存表中的数据:article_categories*/ 


/* 转存表中的数据:articles*/ 


/* 转存表中的数据:attachments*/ 


/* 转存表中的数据:carousels*/ 


/* 转存表中的数据:logs*/ 


/* 转存表中的数据:migrations*/ 
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('1','2016_06_01_000001_create_oauth_auth_codes_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('2','2016_06_01_000002_create_oauth_access_tokens_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('3','2016_06_01_000003_create_oauth_refresh_tokens_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('4','2016_06_01_000004_create_oauth_clients_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('5','2016_06_01_000005_create_oauth_personal_access_clients_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('6','2019_01_11_000000_create_tables_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('7','2019_01_12_000000_create_status_maps_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('8','2019_01_12_000001_create_users_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('9','2019_01_12_000002_create_logs_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('10','2019_01_12_000003_create_permission_tables','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('11','2019_01_12_000004_seed_roles_and_permissions_data','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('12','2019_01_17_123220_create_attachments_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('13','2019_02_21_144853_create_table_bak_records_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('14','2019_02_24_180051_create_system_configs_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('15','2019_02_27_144740_create_article_categories_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('16','2019_02_27_230328_create_tags_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('17','2019_03_06_134918_create_articles_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('18','2019_03_06_150428_create_model_has_tags_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('19','2019_03_12_143515_create_admin_messages_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('20','2019_03_12_144116_create_app_messages_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('21','2019_03_22_164307_create_carousels_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('22','2019_04_18_172820_create_smses_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('23','2019_04_19_083945_create_app_versions_table','1');
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES ('24','2019_05_05_095351_create_user_agreements_table','1');


/* 转存表中的数据:model_has_permissions*/ 


/* 转存表中的数据:model_has_roles*/ 
INSERT INTO `model_has_roles` (`role_id`,`model_type`,`model_id`) VALUES ('1','App\Models\User','1');
INSERT INTO `model_has_roles` (`role_id`,`model_type`,`model_id`) VALUES ('2','App\Models\User','2');
INSERT INTO `model_has_roles` (`role_id`,`model_type`,`model_id`) VALUES ('3','App\Models\User','3');


/* 转存表中的数据:model_has_tags*/ 


/* 转存表中的数据:oauth_access_tokens*/ 
INSERT INTO `oauth_access_tokens` (`id`,`user_id`,`client_id`,`name`,`scopes`,`revoked`,`created_at`,`updated_at`,`expires_at`) VALUES ('5d83f70ac485d2caef46366d00876babb13945e2fdd18eba500ff5e193c0450034bb1a592d6e878e','1','4','','["*"]','0','2019-06-09 21:32:29','2019-06-09 21:32:29','2019-06-12 21:32:29');


/* 转存表中的数据:oauth_auth_codes*/ 


/* 转存表中的数据:oauth_clients*/ 
INSERT INTO `oauth_clients` (`id`,`user_id`,`name`,`secret`,`redirect`,`personal_access_client`,`password_client`,`revoked`,`created_at`,`updated_at`) VALUES ('1','','lucmsee Personal Access Client','0aPStpcgHqPYzghHTSJhU26SykYaRoJBSeo8MBam','http://localhost','1','0','0','2019-06-09 21:31:19','2019-06-09 21:31:19');
INSERT INTO `oauth_clients` (`id`,`user_id`,`name`,`secret`,`redirect`,`personal_access_client`,`password_client`,`revoked`,`created_at`,`updated_at`) VALUES ('2','','lucmsee Password Grant Client','BA4ysEzndsEFtcydNUHHoJTVdX2mienKOtZT12AH','http://localhost','0','1','0','2019-06-09 21:31:19','2019-06-09 21:31:19');
INSERT INTO `oauth_clients` (`id`,`user_id`,`name`,`secret`,`redirect`,`personal_access_client`,`password_client`,`revoked`,`created_at`,`updated_at`) VALUES ('3','','lucmsee Personal Access Client','X2i4BRDcubGV6JrfE6FjJw84Chob39u8EWjziNwg','http://localhost','1','0','0','2019-06-09 21:31:29','2019-06-09 21:31:29');
INSERT INTO `oauth_clients` (`id`,`user_id`,`name`,`secret`,`redirect`,`personal_access_client`,`password_client`,`revoked`,`created_at`,`updated_at`) VALUES ('4','','lucmsee Password Grant Client','l0DoRICDNBLYkfAoPROy7BkG7kMxZt9fcOwiUsEZ','http://localhost','0','1','0','2019-06-09 21:31:29','2019-06-09 21:31:29');


/* 转存表中的数据:oauth_personal_access_clients*/ 
INSERT INTO `oauth_personal_access_clients` (`id`,`client_id`,`created_at`,`updated_at`) VALUES ('1','1','2019-06-09 21:31:19','2019-06-09 21:31:19');
INSERT INTO `oauth_personal_access_clients` (`id`,`client_id`,`created_at`,`updated_at`) VALUES ('2','3','2019-06-09 21:31:29','2019-06-09 21:31:29');


/* 转存表中的数据:oauth_refresh_tokens*/ 
INSERT INTO `oauth_refresh_tokens` (`id`,`access_token_id`,`revoked`,`expires_at`) VALUES ('45ade138d649a2c66c42469a2e8df16c8cd673eea4efde1baa1a62e73d617696fed3dd093887d84a','5d83f70ac485d2caef46366d00876babb13945e2fdd18eba500ff5e193c0450034bb1a592d6e878e','0','2019-06-12 21:32:30');


/* 转存表中的数据:permissions*/ 
INSERT INTO `permissions` (`id`,`name`,`guard_name`,`description`,`remark`,`created_at`,`updated_at`) VALUES ('1','manage_contents','web','管理内容','','2019-06-09 21:30:26','2019-06-09 21:30:26');
INSERT INTO `permissions` (`id`,`name`,`guard_name`,`description`,`remark`,`created_at`,`updated_at`) VALUES ('2','manage_users','web','管理用户','','2019-06-09 21:30:26','2019-06-09 21:30:26');
INSERT INTO `permissions` (`id`,`name`,`guard_name`,`description`,`remark`,`created_at`,`updated_at`) VALUES ('3','edit_settings','web','数据添加修改','','2019-06-09 21:30:26','2019-06-09 21:30:26');


/* 转存表中的数据:role_has_permissions*/ 
INSERT INTO `role_has_permissions` (`permission_id`,`role_id`) VALUES ('1','1');
INSERT INTO `role_has_permissions` (`permission_id`,`role_id`) VALUES ('2','1');
INSERT INTO `role_has_permissions` (`permission_id`,`role_id`) VALUES ('3','1');
INSERT INTO `role_has_permissions` (`permission_id`,`role_id`) VALUES ('1','2');
INSERT INTO `role_has_permissions` (`permission_id`,`role_id`) VALUES ('1','3');


/* 转存表中的数据:roles*/ 
INSERT INTO `roles` (`id`,`name`,`guard_name`,`description`,`remark`,`created_at`,`updated_at`) VALUES ('1','Founder','web','站长','','2019-06-09 21:30:26','2019-06-09 21:30:26');
INSERT INTO `roles` (`id`,`name`,`guard_name`,`description`,`remark`,`created_at`,`updated_at`) VALUES ('2','Maintainer','web','管理员','','2019-06-09 21:30:26','2019-06-09 21:30:26');
INSERT INTO `roles` (`id`,`name`,`guard_name`,`description`,`remark`,`created_at`,`updated_at`) VALUES ('3','WebsiteEditor','web','网站编辑','','2019-06-09 21:30:27','2019-06-09 21:30:27');


/* 转存表中的数据:smses*/ 


/* 转存表中的数据:status_maps*/ 
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('1','users','enable','T','启用','是否启用');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('2','users','enable','F','禁用','是否启用');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('3','users','is_admin','T','是','是否为后台管理员：可登录');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('4','users','is_admin','F','否','是否为后台管理员：不可登录');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('5','logs','type','insert','新增','新增数据');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('6','logs','type','update','修改','修改数据');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('7','logs','type','destroy','删除','删除数据');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('8','logs','type','error','错误日志','错误日志记录');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('9','attachments','file_type','file','文件','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('10','attachments','file_type','pic','图片','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('11','attachments','file_type','video','视频','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('12','attachments','category','lucmsee','系统附件','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('13','attachments','category','avatar','用户头像','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('14','attachments','category','article_cover_img','文章封面','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('15','attachments','category','editor_article_content','富文本文章内容','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('16','attachments','category','markdown_editor_article_content','markdown富文本文章内容','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('17','attachments','category','carousel','轮播图','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('18','attachments','category','tmp','时临图片','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('19','attachments','category','api_img','api上传的图片','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('20','attachments','category','apk','安装包','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('21','attachments','category','editor_agreement_content','markdown富文本用户协议内容','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('22','system_configs','enable','T','启用','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('23','system_configs','enable','F','禁用','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('24','article_categories','enable','T','启用','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('25','article_categories','enable','F','禁用','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('26','articles','enable','T','启用','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('27','articles','enable','F','禁用','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('28','articles','recommend','T','启用','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('29','articles','recommend','F','禁用','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('30','articles','top','T','是','置顶');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('31','articles','top','F','否','不置顶');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('32','articles','access_type','pub','公开','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('33','articles','access_type','pri','私密','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('34','articles','access_type','pwd','密码访问','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('35','admin_messages','message_type','system','系统消息','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('36','admin_messages','message_type','suggest','意建反馈','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('37','admin_messages','is_read','T','已读','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('38','admin_messages','is_read','F','未读','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('39','app_messages','message_type','system','系统消息','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('40','app_messages','is_read','T','已读','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('41','app_messages','is_read','F','未读','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('42','app_messages','is_alert_at_home','T','是','是否在首页弹出');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('43','app_messages','is_alert_at_home','F','否','是否在首页弹出');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('44','app_versions','name','lucmsee','lucmsee','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('45','app_versions','mobile_os','android','Android','android用户');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('46','app_versions','mobile_os','ios','Ios','ios用户');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('47','app_versions','mobile_os','all','All','ios与android共用一个包');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('48','app_versions','is_force_update','T','是','强制更新');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('49','app_versions','is_force_update','F','否','非强制更新');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('50','smses','type','test','测试短信','');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('51','user_agreements','enable','T','启用','是否启用');
INSERT INTO `status_maps` (`id`,`table_name`,`column`,`status_code`,`status_description`,`remark`) VALUES ('52','user_agreements','enable','F','禁用','是否启用');


/* 转存表中的数据:system_configs*/ 
INSERT INTO `system_configs` (`id`,`flag`,`title`,`config_group`,`value`,`description`,`enable`) VALUES ('1','max_bak_sql_file_size','数据库备份每个文件最大字节(单位为M)','basic','20','','T');


/* 转存表中的数据:table_bak_records*/ 


/* 转存表中的数据:tables*/ 
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('1','tables','所有表管理','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('2','status_maps','表状态管理','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('3','users','用户表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('4','logs','日志表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('5','permissions','权限表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('6','model_has_permissions','权限关联表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('7','roles','角色表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('8','model_has_roles','角色关联表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('9','attachments','附件表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('10','system_configs','系统配置表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('11','article_categories','文章分类表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('12','articles','文章表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('13','tags','标签表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('14','smses','短信记录表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('15','app_versions','app版本管理表','');
INSERT INTO `tables` (`id`,`table_name`,`table_name_cn`,`remark`) VALUES ('16','user_agreements','用户协议','');


/* 转存表中的数据:tags*/ 


/* 转存表中的数据:user_agreements*/ 


/* 转存表中的数据:users*/ 
INSERT INTO `users` (`id`,`real_name`,`nickname`,`avatar`,`mobile`,`email`,`password`,`enable`,`is_admin`,`description`,`remark`,`remember_token`,`last_login_at`,`created_at`,`updated_at`) VALUES ('1','张老大','ucer','','','dev@lucms.com','$2y$10$9M3rM47TIaZHRwUITVpKPe41vT2sCcIMcuhs9BRNTyaXN1SNaQzFS','T','T','Quod incidunt autem quidem minus voluptatem cumque minus quasi.','','DKvNKy9nAw','2019-06-09 21:32:29','2019-06-09 21:31:10','2019-06-09 21:32:29');
INSERT INTO `users` (`id`,`real_name`,`nickname`,`avatar`,`mobile`,`email`,`password`,`enable`,`is_admin`,`description`,`remark`,`remember_token`,`last_login_at`,`created_at`,`updated_at`) VALUES ('2','管理员','小管家','','','xgj@lucms.com','$2y$10$9M3rM47TIaZHRwUITVpKPe41vT2sCcIMcuhs9BRNTyaXN1SNaQzFS','T','T','Totam autem quis aliquid fugit maiores.','','kTd50ftVmk','','2019-06-09 21:31:10','2019-06-09 21:31:10');
INSERT INTO `users` (`id`,`real_name`,`nickname`,`avatar`,`mobile`,`email`,`password`,`enable`,`is_admin`,`description`,`remark`,`remember_token`,`last_login_at`,`created_at`,`updated_at`) VALUES ('3','网站编辑','小编辑','','','xbj@lucms.com','$2y$10$9M3rM47TIaZHRwUITVpKPe41vT2sCcIMcuhs9BRNTyaXN1SNaQzFS','T','T','Aspernatur animi distinctio quam non exercitationem quasi voluptate tempora.','','bqWA1pmXPU','','2019-06-09 21:31:10','2019-06-09 21:31:11');
