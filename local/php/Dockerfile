FROM php:8-fpm
RUN apt-get update --fix-missing --no-install-recommends \
    && apt-get install -y \
        curl \
        libzip-dev \
        zip \
        libonig-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libmagickwand-dev \
        supervisor \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd exif iconv pdo pdo_mysql mbstring pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && mkdir -p /usr/src/php/ext/imagick; \
    curl -fsSL https://github.com/Imagick/imagick/archive/06116aa24b76edaf6b1693198f79e6c295eda8a9.tar.gz | tar xvz -C "/usr/src/php/ext/imagick" --strip 1 \
    && docker-php-ext-install imagick \
    && docker-php-source delete \
    && apt-get clean

ADD ./conf/upload.ini /usr/local/etc/php/conf.d/upload.ini
ADD ./conf/memory-limit.ini /usr/local/etc/php/conf.d/memory-limit.ini
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
