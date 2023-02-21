FROM composer:2.4.4 as vendor
# composer
WORKDIR /tmp
ADD ./app ./vendor
RUN cd vendor && composer install --optimize-autoloader --no-dev --no-scripts

# node
FROM node:14.21.3 as node
WORKDIR /tmp
ADD ./app ./node
RUN cd node && \
    npm install laravel-mix@6.0.49 --save-dev && \
    npm run prod


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
COPY --from=node /tmp/node /var/www/html
COPY --from=composer:2.4.4 /usr/bin/composer /usr/bin/composer
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

WORKDIR /var/www/html

# Environment
touch .env
ARG APP_DEBUG
ARG APP_ENV
ARG APP_KEY
ARG APP_NAME
ARG AWS_ACCESS_KEY_ID
ARG AWS_BUCKET
ARG AWS_DEFAULT_REGION
ARG AWS_ENDPOINT
ARG AWS_PATH_STYLE_ENDPOINT
ARG AWS_SECRET_ACCESS_KEY
ARG BROADCAST_DRIVER
ARG CACHE_DRIVER
ARG DB_CONNECTION
ARG DB_HOST
ARG DB_PORT
ARG LOG_CHANNEL
ARG LOG_CHANNELS_1
ARG LOG_CHANNELS_2
ARG LOG_CHANNELS_3
ARG LOG_LEVEL
ARG QUEUE_CONNECTION
ARG QUEUE_FAILED_DRIVER
ARG REDIS_HOST
ARG REDIS_PASSWORD
ARG REDIS_PORT
ARG SESSION_DRIVER
ARG SESSION_LIFETIME
RUN echo "APP_DEBUG=${APP_DEBUG}" > .env && \
    echo "APP_ENV=${APP_ENV}" > .env && \
    echo "APP_KEY=${APP_KEY}" > .env && \
    echo "APP_NAME=${APP_NAME}" > .env && \
    echo "AWS_ACCESS_KEY_ID=${AWS_ACCESS_KEY_ID}" > .env && \
    echo "AWS_BUCKET=${AWS_BUCKET}" > .env && \
    echo "AWS_DEFAULT_REGION=${AWS_DEFAULT_REGION}" > .env && \
    echo "AWS_ENDPOINT=${AWS_ENDPOINT}" > .env && \
    echo "AWS_PATH_STYLE_ENDPOINT=${AWS_PATH_STYLE_ENDPOINT}" > .env && \





# script
ADD ./build/start.sh /start.sh
RUN chmod +x /start.sh \
    && mkdir -p /run/nginx

EXPOSE 80
CMD ["/start.sh"]
