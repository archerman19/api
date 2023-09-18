FROM php:8.2-fpm

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN pecl install -o -f xdebug \
	&& docker-php-ext-enable xdebug

COPY ./docker/app/php.ini /usr/local/etc/php/
COPY ./src /var/www/html

CMD  ["php-fpm"]