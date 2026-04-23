# genomed

Создать Базу данных genomed
В конфиге config/db.php прописать доступы к базе

#Config for NGINX

server {
    listen 80;
    server_name www.genomed.loc;
    rewrite ^(.+)$ http://genomed.loc permanent;
}

server {
    charset utf-8;
    client_max_body_size 128M;

    listen 80; ## listen for ipv4

    server_name genomed.loc www.genomed.loc;
    root   /var/www/genomed/web;
    index  index.php;

    access_log /var/log/nginx/genomed.access.log;
    error_log  /var/log/nginx/genomed.error.log;

    location / {
        # Redirect everything that isn't a real file to index.php
        try_files $uri $uri/ /index.php?$args;
    }

    # uncomment to avoid processing of calls to non-existing static files by Yii
    #location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ {
    #    try_files $uri =404;
    #}
    #error_page 404 /404.html;

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+?\.php)(/.*)$;
        if (!-f $document_root$fastcgi_script_name) {
                return 404;
        }

        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param SCRIPT_NAME $fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 600s;
    }

    location ~ /\.(git) {
        deny all;
    }
}

#composer

composer install

#Создать базу genomed и прописать в конфиге доступы к ней.

#накатить миграции
