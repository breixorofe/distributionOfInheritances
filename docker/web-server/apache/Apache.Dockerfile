FROM apache

RUN a2enmod proxy_fcgi setenvif
RUN a2enconf php8.0-fpm