FROM composer:latest as vendor
ARG GITHUB_TOKEN
# composer
ADD ./app/composer.json /tmp/vendor/composer.json
ADD ./app/composer.lock /tmp/vendor/composer.lock
RUN composer config --global github-oauth.github.com ${GITHUB_TOKEN} && \
    cd /tmp/vendor && composer install --no-dev

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
ADD ./app /var/www/html
ADD ./build/nginx/error /var/www/error
RUN chown -R www-data:www-data /var/www/html
RUN chown -R www-data:www-data /var/www/error

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
