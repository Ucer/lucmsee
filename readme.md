## 开发环境部署/安装

基于 Laravel5.7.* 与 iview3.2.1 开发的后台管理系统

### 系统依赖

运行系统必须要先满足以下的环境

####  lucmsee

- Laravel5.8.*
- Php7.1.*
- Mysql8.0.*
- Nginx1.14.0
- composer1.8.4
- barryvdh/laravel-cors:^0.11.2
- fideloper/proxy:^4.0
- laravel/framewor:5.8.*
- laravel/passport:^7.0
- laravel/tinker: ^1.0


#### lu 

- node 11.0.0
- node-sass 
 

## 功能模块

### 菜单

- () 中为对应的表名称
- [] 中为用户需要拥有相关角色才可访问功能

#### 系统管理 ['Founder']
- 数据库监控
    - 表备份
    - 查看备份记录(table_bak_records)
        - 下载备份文件
        - 批量删除备份记录
    - 表优化
    - 表修复
- 数据字典(tables)
    - 添加表
    - 修改表
    - 删除表
    - 表字典管理(status_maps)
        - 添加状态
        - 修改状态
        - 删除状态
- 系统配置 （可以在后台修改的配置项:CURD）

#### 资源管理 ['Founder', 'Maintainer']
- 附件管理(attachments)
    - 上传(图片、文件、视频)
    - 删除
- 图片管理(attachments中的图片)

#### 权限管理 ['Founder', 'Maintainer', 'WebsiteEditor']
- 权限管理(permissions) ['Founder']
    - 添加
    - 修改
    - 删除
- 角色管理(roles) ['Founder', 'Maintainer']
    - 添加
    - 修改
    - 删除
    - 启用禁用
    - 分配权限(model_has_permissions)
- 用户管理(users) ['Founder', 'Maintainer', 'WebsiteEditor']
    - 添加  ['Founder', 'Maintainer']
    - 修改  ['Founder', 'Maintainer']
    - 删除  ['Founder', 'Maintainer']
    - 分配角色(model_has_roles) ['Founder', 'Maintainer']

#### 新闻系统 ['Founder', 'Maintainer', 'WebsiteEditor']
- 标签管理(tags)
    - 添加
    - 修改
    - 删除
    - excel 导入
- 文章分类(article_categories)
    - 添加
    - 修改
    - 删除
    - 快速排序
- 文章列表(articles)
    - 添加
        - 新建标签(tags)
    - 修改
    - 删除
    - 启用禁用
    - 置顶
    - 推荐
- 轮播图(carousels)
    - 添加
    - 修改
    - 删除
    - 快速排序

#### 消息中心 ['Founder', 'Maintainer']
- 站内信(app_messages)
    - 发消息给 user (发给指定用户或群发)
    - 详细
    - 删除
    - 批量删除
- 后台消息(admin_messages)
    - 发消息给 adminer (发给指定用户或群发)
    - 一键已读
    - 详细
    - 删除
    - 批量删除
    

#### 系统安全 ['Founder', 'Maintainer', 'WebsiteEditor']
-系统日志(logs)
    - 详细
    - 删除
    - excel 导出

## 其它功能

- 登录登出
- 多言语切换
- 集成图片裁剪工具
- 管理员重置自己的登录密码
- echarts 图表



## 生成环境

- 禁用跨域 (移除Kernel.php中的middleware)

## 问题

- 软删除会不会导致什么问题

- 如果添加修改数据过程中，提示xx已存在，但是在列表中又找不到的情况，很有可能是因为数据只是被软删除导致
