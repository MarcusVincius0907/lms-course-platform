FROM php:7.4-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY ./apache/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
COPY . /var/www/html/
EXPOSE 80
