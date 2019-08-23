##  常用操作

```text
php artisan queue:work redis --tries=3 --sleep=3 
```

## superversion

> sudo apt-get install supervisor

`/etc/supervisor/conf.d/laravel-worker.conf`
```ssh
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=/usr/local/php/bin/php /datadisk/developer/honghedebao_api/artisan queue:work redis  --sleep=3 --tries=3
autostart=true
autorestart=true
user=root #改成你自己的
numprocs=8
redirect_stderr=true
stdout_logfile=/tmp/laravel-worker.log

[program:laravel-worker-horizon]
process_name=%(program_name)s
command=/usr/local/php/bin/php /datadisk/developer/honghedebao_api/artisan horizon
autostart=true
autorestart=true
user=root
redirect_stderr=true
stdout_logfile=/tmp/laravel-worker-horizon.log

```

在这个例子中，numprocs 指令将指定 Supervisor 运行 8 个 queue:work 进程并对其进行监控，如果它们挂掉就自动重启它们。

配置文件创建完毕后，你就可以使用如下命令更新 Supervisor 配置并启动进程了，注意每次改动配置文件后都需要重新执行此操作

```text
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

## 注意

记住，队列处理器是一个常驻的进程并且在内存中保存着已经启动的应用状态。

因此，它们并不会在启动后注意到你代码的更改。所以，在你的重新部署过程中，请记得 重启你的队列处理器.

> php artisan queue:restart

## 处理失败的任务

有时你的队列化任务会执行失败。放平心态，好事多磨。

Laravel 包含了一种方便的方法来指定任务应该尝试的最大次数。
 
如果一个任务已经到达了最大尝试次数，它就会被插入到 failed_jobs 数据库表中。
 
要创建 failed_jobs 数据库迁移表，你可以使用 queue:failed-table 命令：


