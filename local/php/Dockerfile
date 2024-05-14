FROM php:8.0-fpm
RUN apt-get update --fix-missing --no-install-recommends \
    && apt-get install -y \
        curl \
        jq \
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
    && pecl install imagick \
    && sed -i -e 's/<policy domain="resource" name="memory" value="256MiB"\/>/<policy domain="resource" name="memory" value="512MiB"\/>/g' /etc/ImageMagick-6/policy.xml \
    && sed -i -e 's/<policy domain="resource" name="area" value="128MP"\/>/<policy domain="resource" name="area" value="512MP"\/>/g' /etc/ImageMagick-6/policy.xml \
    && sed -i -e 's/<policy domain="resource" name="width" value="16KP"\/>/<policy domain="resource" name="width" value="64KP"\/>/g' /etc/ImageMagick-6/policy.xml \
    && sed -i -e 's/<policy domain="resource" name="height" value="16KP"\/>/<policy domain="resource" name="height" value="64KP"\/>/g' /etc/ImageMagick-6/policy.xml \
    && docker-php-ext-enable imagick \
    && docker-php-source delete \
    && apt-get clean

ADD ./conf/upload.ini /usr/local/etc/php/conf.d/upload.ini
ADD ./conf/memory-limit.ini /usr/local/etc/php/conf.d/memory-limit.ini
# ADD ./conf/imagick.ini /usr/local/etc/php/conf.d/imagick.ini
ADD ./cron/crontab /var/spool/cron/crontabs/root
ADD ./supervisor/conf.d/cron.conf /etc/supervisor/conf.d/cron.conf
ADD ./supervisor/conf.d/roda.conf /etc/supervisor/conf.d/roda.conf

# Node LTS
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV PATH $PATH:/home/root/.composer/vendor/bin

WORKDIR /var/www/html
