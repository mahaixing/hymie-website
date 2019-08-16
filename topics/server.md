## Nginx 配置   

以下配置启用了 Pathinfo 和 URL Rewrite。

```
server {
    listen       80;
    server_name  hymie.iautoo.cn;

    #charset koi8-r;
    #access_log  /var/log/nginx/host.access.log  main;

    location / {
            root /home/fourbyte/hymie-website;
            index index.php index.html index.htm;
            # URL 重写
            if (!-e $request_filename) {
                rewrite  ^(.*)$ /index.php$1 last;
                break;
            }
    }

    #需要去掉最后的 $ 否则无法匹配带 path_info 的 URL
    location ~ \.php {
        #根据需要指向项目主目录
        root         /home/fourbyte/hymie-website;

        #如果使用ip
        fastcgi_pass    127.0.0.1:9000;

        #如果使用sock
        #fastcgi_pass    unix:path_to_php-fcgi.sock;

        fastcgi_index   index.php;

        #启用pathinfo(项目中URL路由默认是 query string)
        fastcgi_split_path_info ^(.+\.php)(/?.+)$;

        #设置 path_info 环境变量, 可以通过 $_SERVER 变量获得
        fastcgi_param  PATH_INFO $fastcgi_path_info;

        #以下是常规配置
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }

    #error_page  404              /404.html;

    # redirect server error pages to the static page /50x.html
    #
    error_page   500 502 503 504  /50x.html;
    location = /50x.html {
        root   /home/fourbyte/www-iautoo;
    }

}
```

## Apache 配置
_待补充_