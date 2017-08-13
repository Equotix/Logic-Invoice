FROM php:7-apache
RUN apt-get update && apt-get install -y \
	libfreetype6-dev \
	libpng12-dev \
	libjpeg62-turbo-dev \
	zlib1g-dev && \
	docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
	docker-php-ext-install -j$(nproc) zip mysqli gd
COPY upload/ /var/www/html/
COPY config-prod.php config.php
RUN mv .htaccess.txt .htaccess && rm -rf install && chown -R www-data:www-data . && a2enmod rewrite

EXPOSE 80
