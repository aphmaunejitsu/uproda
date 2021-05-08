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
    && mkdir -p /usr/src/php/ext/imagick; \
    curl -fsSL https://github.com/Imagick/imagick/archive/448c1cd0d58ba2838b9b6dff71c9b7e70a401b90.tar.gz | tar xvz -C "/usr/src/php/ext/imagick" --strip-components 1 \
    && cd /usr/src/php/ext/imagick \
    && phpize && ./configure \
    && make && make install \
    && docker-php-source delete \
    && apt-get clean

# RUN mkdir -p /usr/local/src/imagick \
#     && curl -fsSL https://github.com/Imagick/imagick/archive/448c1cd0d58ba2838b9b6dff71c9b7e70a401b90.tar.gz | tar -zxv -C "/usr/local/src/imagick" --strip-components 1 \
#     && cd /usr/local/src/imagick \
#     && phpize && ./configure \
#     && make && make install

ADD ./conf/upload.ini /usr/local/etc/php/conf.d/upload.ini
ADD ./conf/memory-limit.ini /usr/local/etc/php/conf.d/memory-limit.ini
ADD ./conf/imagick.ini /usr/local/etc/php/conf.d/imagick.ini
ADD ./cron/crontab /var/spool/cron/crontabs/root
ADD ./supervisor/conf.d/cron.conf /etc/supervisor/conf.d/cron.conf
ADD ./supervisor/conf.d/roda.conf /etc/supervisor/conf.d/roda.conf

# Node
RUN curl -fsSL https://deb.nodesource.com/setup_15.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV PATH $PATH:/home/root/.composer/vendor/bin

WORKDIR /var/www/html
