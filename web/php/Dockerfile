FROM php:7-fpm
RUN apt-get update --fix-missing \
    && apt-get install -y \
        locales \
        libmemcached-dev \
        curl \
        libzip-dev \ 
        zip \
        libonig-dev \
    && docker-php-ext-install -j$(nproc) iconv pdo pdo_mysql mbstring exif pcntl \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - \
 && apt-get install -y nodejs

#install Imagemagick & PHP Imagick ext
RUN apt-get update && apt-get install -y \
        libmagickwand-dev --no-install-recommends \
        imagemagick
RUN pecl install imagick && docker-php-ext-enable imagick

ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN groupadd -g ${PGID} aphmau && \
    useradd -u ${PUID} -g aphmau -m aphmau && \
    usermod -p "*" aphmau -s /bin/sh

#composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV PATH $PATH:/home/aphmau/.composer/vendor/bin
RUN composer config -g repos.packagist composer https://packagist.jp && composer global require hirak/prestissimo
# RUN service supervisor start

RUN locale-gen ja_JP.UTF-8
ENV LANG ja_JP.UTF-8
ENV LC_CTYPE ja_JP.UTF-8
RUN localedef -f UTF-8 -i ja_JP ja_JP.utf8
COPY etc/upload.ini /usr/local/etc/php/conf.d/

WORKDIR /var/www/roda
