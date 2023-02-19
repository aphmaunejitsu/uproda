FROM composer:2.4.4 as vendor
# composer
WORKDIR /tmp
ADD ./app/composer.json ./vendor/composer.json
ADD ./app/composer.lock ./vendor/composer.lock
ADD ./app/database ./vendor/database
ADD ./app/tests ./vendor/tests
RUN cd ./vendor && composer install --optimize-autoloader --no-dev --no-scripts

# node
FROM node:14.21.3 as node
WORKDIR /tmp
ADD ./app/package.json ./package.json
ADD ./app/package-lock.json ./package-lock.json
ADD ./app/resources ./resources
ADD ./app/webpack.mix.js ./webpack.mix.js
ADD ./app/public /public
RUN npm install laravel-mix@6.0.49 --save-dev
RUN npm run prod


FROM php:8-fpm
RUN apt-get update --fix-missing --no-install-recommends \
    && apt-get install -y \
        curl \
        libzip-dev \
        zip \
        libonig-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        imagemagick \
        libmagickwand-dev \
        nginx \
        supervisor --no-install-recommends \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ --with-webp=/usr/include \
    && docker-php-ext-install -j$(nproc) gd exif iconv pdo pdo_mysql mbstring pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && pecl install -o -f imagick \
    && docker-php-ext-enable imagick \
    && docker-php-source delete \
    && apt-get clean

# mo
COPY --from=metal3d/mo /usr/local/bin/mo /usr/bin/mo

# Copy Source
COPY --from=vendor /tmp/vendor /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --from=node /public /var/www/html/public
COPY --from=node /tmp /var/www/html
ADD ./app /var/www/html
ADD ./build/nginx/error /var/www/error
RUN chown -R www-data:www-data /var/www/html
RUN chown -R www-data:www-data /var/www/error
RUN cd /var/www/html && composer install --optimize-autoloader --no-dev

# supervisor conf
ADD ./build/supervisor/supervisor.conf /etc/supervisor.conf
ADD ./build/supervisor/conf.d/php-fpm.conf /etc/supervisor/conf.d/php-fpm.conf
ADD ./build/supervisor/conf.d/nginx.conf /etc/supervisor/conf.d/nginx.conf

# nginx conf
ADD ./build/nginx/conf.d/log-json-format.conf /etc/nginx/http.d/00-log-json-format.conf
ADD ./build/nginx/conf.d/default.conf.mustache /tmp/default.conf.mustache

# php conf
ADD ./build/php/conf.d/upload.ini /usr/local/etc/php/conf.d/upload.ini
ADD ./build/php/conf.d/memory-limit.ini /usr/local/etc/php/conf.d/memory-limit.ini

# crontab
ADD ./build/cron/crontab /var/spool/cron/crontabs/root

# script
ADD ./build/start.sh /start.sh
RUN chmod +x /start.sh \
    && mkdir -p /run/nginx

EXPOSE 80
WORKDIR /var/www/html
CMD ["/start.sh"]
