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
RUN touch .env
ARG APP_DEBUG
ARG APP_ENV
ARG APP_NAME
ARG APP_URL

ARG AWS_BUCKET
ARG AWS_DEFAULT_REGION
ARG AWS_ENDPOINT
ARG AWS_PATH_STYLE_ENDPOINT

ARG BROADCAST_DRIVER
ARG CACHE_DRIVER

ARG LOG_CHANNEL
ARG LOG_CHANNELS_1
ARG LOG_CHANNELS_2
ARG LOG_CHANNELS_3
ARG LOG_LEVEL

ARG QUEUE_CONNECTION
ARG QUEUE_FAILED_DRIVER

ARG REDIS_HOST
ARG REDIS_PORT

ARG RODA_ACCEPT_FILES
ARG RODA_DESCRIPTION

ARG RODA_GOOGLE_RECAPTCHA_CACHE

ARG RODA_IMAGE_STORAGE
ARG RODA_IMG_DESCRIPTION
ARG RODA_LATITUDE
ARG RODA_LONGITUDE
ARG RODA_NAME
ARG RODA_PAGINATION_PERPAGE
ARG RODA_RENRAKU_NAME
ARG RODA_RENRAKU_TWITTER

ARG RODA_SERVICE_CASHE
ARG RODA_SUBTITLE
ARG RODA_THUMBNAIL_HEIGHT
ARG RODA_THUMBNAIL_WIDTH
ARG RODA_TOR_URL
ARG RODA_TWITTER_ACCOUNT
ARG RODA_UPLOAD_CHUNK
ARG RODA_UPLOAD_MAXSIZE
ARG RODA_URL_IMAGE_BASE
ARG RODA_WAIT_TIME

ARG SESSION_DRIVER
ARG SESSION_LIFETIME

ENV APP_DEBUG=$APP_DEBUG
ENV APP_ENV=$APP_ENV
ENV APP_NAME=$APP_NAME
ENV APP_URL=$APP_URL

ENV AWS_BUCKET=$AWS_BUCKET
ENV AWS_DEFAULT_REGION=$AWS_DEFAULT_REGION
ENV AWS_ENDPOINT=$AWS_ENDPOINT
ENV AWS_PATH_STYLE_ENDPOINT=$AWS_PATH_STYLE_ENDPOINT

ENV BROADCAST_DRIVER=$BROADCAST_DRIVER
ENV CACHE_DRIVER=$CACHE_DRIVER

ENV LOG_CHANNEL=$LOG_CHANNEL
ENV LOG_CHANNELS_1=$LOG_CHANNELS_1
ENV LOG_CHANNELS_2=$LOG_CHANNELS_2
ENV LOG_CHANNELS_3=$LOG_CHANNELS_3
ENV LOG_LEVEL=$LOG_LEVEL

ENV QUEUE_CONNECTION=$QUEUE_CONNECTION
ENV QUEUE_FAILED_DRIVER=$QUEUE_FAILED_DRIVER

ENV REDIS_HOST=$REDIS_HOST
ENV REDIS_PORT=$REDIS_PORT

ENV RODA_ACCEPT_FILES=$RODA_ACCEPT_FILES
ENV RODA_DESCRIPTION=$RODA_DESCRIPTION

ENV RODA_GOOGLE_RECAPTCHA_CACHE=$RODA_GOOGLE_RECAPTCHA_CACHE

ENV RODA_IMAGE_STORAGE=$RODA_IMAGE_STORAGE
ENV RODA_IMG_DESCRIPTION=$RODA_IMG_DESCRIPTION
ENV RODA_LATITUDE=$RODA_LATITUDE
ENV RODA_LONGITUDE=$RODA_LONGITUDE
ENV RODA_NAME=$RODA_NAME
ENV RODA_PAGINATION_PERPAGE=$RODA_PAGINATION_PERPAGE
ENV RODA_RENRAKU_NAME=$RODA_RENRAKU_NAME
ENV RODA_RENRAKU_TWITTER=$RODA_RENRAKU_TWITTER

ENV RODA_SERVICE_CASHE=$RODA_SERVICE_CASHE
ENV RODA_SUBTITLE=$RODA_SUBTITLE
ENV RODA_THUMBNAIL_HEIGHT=$RODA_THUMBNAIL_HEIGHT
ENV RODA_THUMBNAIL_WIDTH=$RODA_THUMBNAIL_WIDTH
ENV RODA_TOR_URL=$RODA_TOR_URL
ENV RODA_TWITTER_ACCOUNT=$RODA_TWITTER_ACCOUNT
ENV RODA_UPLOAD_CHUNK=$RODA_UPLOAD_CHUNK
ENV RODA_UPLOAD_MAXSIZE=$RODA_UPLOAD_MAXSIZE
ENV RODA_URL_IMAGE_BASE=$RODA_URL_IMAGE_BASE
ENV RODA_WAIT_TIME=$RODA_WAIT_TIME

ENV SESSION_DRIVER=$SESSION_DRIVER
ENV SESSION_LIFETIMEA=$SESSION_LIFETIME

ENV MIX_RODA_NAME=$RODA_NAME
ENV MIX_APP_URL=$APP_URL
ENV MIX_RODA_SUBTITLE=$RODA_SUBTITLE
ENV MIX_RODA_DESCRIPTION=$RODA_DESCRIPTION
ENV MIX_RODA_IMG_DESCRIPTION=$RODA_IMG_DESCRIPTION

ENV MIX_RODA_TWITTER_ACCOUNT=$RODA_TWITTER_ACCOUNT
ENV MIX_RODA_UPLOAD_MAXSIZE=$RODA_UPLOAD_MAXSIZE
ENV MIX_RODA_ACCEPT_FILES=$RODA_ACCEPT_FILES

ENV MIX_RODA_UPLOAD_CHUNK=$RODA_UPLOAD_CHUNK

ENV MIX_RODA_WAIT_TIME=$RODA_WAIT_TIME

ENV MIX_RODA_RENRAKU_NAME=$RODA_RENRAKU_NAME
ENV MIX_RODA_RENRAKU_TWITTER=$RODA_RENRAKU_TWITTER

# script
ADD ./build/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80
CMD ["/start.sh"]
