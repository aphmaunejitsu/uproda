FROM php:7.0-fpm
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv mcrypt pdo pdo_mysql zip mbstring exif\
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

#composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    chmod 777 /usr/local/bin/composer
#この辺でいい具合にcomposerをインストール

RUN cd /var/www && \
    curl https://get.fuelphp.com/oil | sh

#WORKDIR /var/www/roda
#RUN /usr/local/bin/php /var/www/roda/oil r install

WORKDIR /var/www
