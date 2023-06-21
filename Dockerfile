FROM php:7.4-apache
RUN apt-get update && apt-get update
RUN apt-get install -y php php-mysql mariadb-server mariadb-client build-essential sudo composer
RUN a2enmod rewrite
RUN service apache2 restart
EXPOSE 80