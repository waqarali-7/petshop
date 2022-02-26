FROM php:8.0-fpm

# Install PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends vim curl debconf subversion git apt-transport-https apt-utils \
    build-essential locales acl mailutils wget nodejs zip unzip \
    gnupg gnupg1 gnupg2 \
    zlib1g-dev \
    zip \
    libzip-dev \
    cron \
    sudo \
    libpng-dev

RUN docker-php-ext-configure zip
RUN docker-php-ext-install pdo pdo_mysql zip gd bcmath
# RUN pecl install redis && docker-php-ext-enable redis

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# install phpunit
RUN curl https://phar.phpunit.de/phpunit-8.phar -L > phpunit.phar \
  && chmod +x phpunit.phar \
  && mv phpunit.phar /usr/local/bin/phpunit \
  && phpunit --version

# RUN wget --no-check-certificate https://phar.phpunit.de/phpunit-6.5.3.phar && \
#     mv phpunit*.phar phpunit.phar && \
#     chmod +x phpunit.phar && \
#     mv phpunit.phar /usr/local/bin/phpunit

COPY php.ini $PHP_INI_DIR/php.ini
COPY php-fpm-pool.conf /etc/php/8.0/pool.d/www.conf

# Change uid and gid of apache to docker user uid/gid
# RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
# RUN chown -R www-data:www-data /var/www/html/

ENV PHP_EXTRA_CONFIGURE_ARGS --enable-fpm --with-fpm-user=www-data --with-fpm-group=www-data

# Add crontab file in the cron directory
ADD crontab /etc/cron.d/cron
# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/cron
# Apply cron job
RUN crontab /etc/cron.d/cron
# Create the log file to be able to run tail
RUN touch /var/log/cron.log
# Run the command on container startup
CMD ["cron", "-f"]
# USER www-data

WORKDIR /var/www/html
