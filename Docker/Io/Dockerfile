FROM php:8.0.9-apache
WORKDIR /Application

# Update & upgrade
RUN apt-get update && apt-get upgrade -y
RUN apt-get install cron libzip-dev unzip wget -y

# Composer & update

COPY ./Docker/Io/composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer
RUN composer self-update

# PHP extensions

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install zip
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install sockets
RUN docker-php-ext-install opcache
RUN a2enmod rewrite

# Copy all into Application
COPY . /Application

# Enable PHP-Opcache
COPY ./Docker/Io/opcache.ini /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

# Apache config & disable default site & enable r3m site
COPY ./Docker/Io/001-r3m.io.conf /etc/apache2/sites-available/001-r3m.io.conf
RUN a2dissite 000-default.conf
RUN a2ensite 001-r3m.io.conf
RUN service apache2 restart

# Execute start script
RUN chmod +x /Application/start.sh
RUN sh   /Application/start.sh
