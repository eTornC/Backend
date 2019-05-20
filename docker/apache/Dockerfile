FROM mattrayner/lamp:latest-1604-php7

RUN apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y php-xdebug; \
    apt-get install -y nano; \
    cp /usr/share/zoneinfo/Europe/Madrid /etc/localtime

COPY xdebug.ini /etc/php/7.2/mods-available/xdebug.ini

EXPOSE 3306

#RUN service mysql start; bash /create_mysql_users.sh
#https://dev.to/brpaz/docker-phpstorm-and-xdebug-the-definitive-guide-14og