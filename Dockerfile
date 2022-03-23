FROM php:8.1.0-fpm-alpine3.15

RUN apk add --no-cache shadow openssl bash mysql-client nodejs npm freetype-dev libjpeg-turbo-dev libpng-dev libzip-dev git $PHPIZE_DEPS && pecl install redis
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-enable redis
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd

RUN touch /home/www-data/.bashrc | echo "PS1='\w\$ '" >> /home/www-data/.bashrc

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .

RUN usermod -u 1000 www-data

WORKDIR /var/www

RUN rm -rf /var/www/html && ln -s public html

USER www-data

EXPOSE 9000
ENTRYPOINT ["php-fpm"]
