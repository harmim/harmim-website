FROM php:7.2-apache

RUN a2enmod rewrite
RUN a2enmod ssl

RUN apt-get update -y
RUN apt-get install -y \
	git \
	wget \
	zip \
	unzip
RUN apt-get clean -y

RUN pecl install xdebug

RUN wget https://getcomposer.org/installer -O composer-setup.php
RUN php composer-setup.php --install-dir=/usr/bin/ --filename=composer --quiet
RUN rm -f composer-setup.php
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN mkdir /root/.ssh && ssh-keyscan github.com >> /root/.ssh/known_hosts
RUN composer global require hirak/prestissimo

RUN service apache2 restart
