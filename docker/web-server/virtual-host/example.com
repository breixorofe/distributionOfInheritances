server {
        listen  80;
        index index.html index.htm index.php;
        server_name localhost;
        root /var/www/distributionOfInheritances/public;
     

        location ~ ^/backoffice(.*)$ {
        index index.php;
        root /var/www/distributionOfInheritances/;
        try_files $uri $uri/ /apps/backoffice/public/index.php?do=$request_uri;

        location ~ \.php$ {
                    try_files $uri =404;
                    fastcgi_split_path_info ^(.+\.php)(/.+)$;
                    fastcgi_pass application:9000;
                    fastcgi_index index.php;
                    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                    include fastcgi_params;
            }

        }

    location ~ ^((?!\/backoffice).)*$ { #this regex is to match anything but `/blog`
        index index.php;
                root /var/www/distributionOfInheritances/public;
                try_files $uri $uri/ /index.php?$request_uri;
        location ~ \.php$ {
                    try_files $uri =404;
                    fastcgi_split_path_info ^(.+\.php)(/.+)$;
                    fastcgi_pass application:9000;
                    fastcgi_index index.php;
                    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                    include fastcgi_params;
            }
        }


        error_page 404 /404.html;
        error_page 500 502 503 504 /50x.html;
        location = /50x.html {
                root /usr/share/nginx/html;
        }


}