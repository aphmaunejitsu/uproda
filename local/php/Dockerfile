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
        libfreetype6-dev \
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
    # && mkdir -p /usr/src/php/ext/imagick; \
    # curl -fsSL https://github.com/Imagick/imagick/archive/ref/tags/3.7.0.tar.gz | tar xvz -C "/usr/src/php/ext/imagick" --strip 1 \
    # && docker-php-ext-install imagick \
    # && cd /usr/src/php/ext/imagick \
    # && phpize && ./configure \
    # && make && make install \
    && docker-php-source delete \
    && apt-get clean

ADD ./conf/upload.ini /usr/local/etc/php/conf.d/upload.ini
ADD ./conf/memory-limit.ini /usr/local/etc/php/conf.d/memory-limit.ini
# ADD ./conf/imagick.ini /usr/local/etc/php/conf.d/imagick.ini
ADD ./cron/crontab /var/spool/cron/crontabs/root
ADD ./supervisor/conf.d/cron.conf /etc/supervisor/conf.d/cron.conf
ADD ./supervisor/conf.d/roda.conf /etc/supervisor/conf.d/roda.conf

# Node
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV PATH $PATH:/home/root/.composer/vendor/bin

WORKDIR /var/www/html
