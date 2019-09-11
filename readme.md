## 系统简介

- laravel5.8
- vue2.0
- iview3.4
- php7.2
- mysql8.0
- redis
- 支持响应式布局
- 前后端完全分离
- 可扩展性高

文档位于  doc 目录下

演示地址： http://lucmsee.codehaoshi.com/dashboard 

演示账号密码： dev@lucms.com   123456


## 功能模块

### 菜单

- () 中为对应的表名称
- [] 中为用户需要拥有相关角色才可访问功能

#### 系统管理 ['Founder']
- 数据库监控 ['Developer']
    - 表备份 (备份目录可在 set_file_path.php 配置文件中更改)
    - 查看备份记录(table_bak_records)
        - 下载备份文件 (格式为 zip)
        - 批量删除备份记录
    - 表优化
    - 表修复
- 数据字典(tables) ['Developer']
    - 添加表
    - 修改表
    - 删除表
    - 表字典管理(status_maps)
        - 添加状态
        - 修改状态
        - 删除状态
- 系统配置
    - 列表
        - 启用禁用
    - 添加
    - 修改
    - 删除
- App版本管理
    - 列表
    - 发布新版本
    - 修改

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
    - 删除  ['Founder', 'Developer']
    - 分配角色(model_has_roles) ['Founder', 'Developer']

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
    - 启用禁用
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
- 系统日志(logs)
    - 详细
    - 删除
    - excel 导出
- 用户协议(user_agreements，支持多种类型的协议) ['Founder', 'Maintainer']
    - 详细
    - 添加
    - 修改
    - 启用禁用
    - 删除
    
#### 系统安全 ['Founder', 'Maintainer', 'WebsiteEditor']

## 其它功能

- 登录登出
- 多言语切换
- 集成图片裁剪工具
- 管理员重置自己的登录密码
- echarts 图表
- 消息队列
- 钉钉机器人
- AES加密算法



