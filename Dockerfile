FROM php:8.2-fpm



# Install Composer
RUN echo "Install COMPOSER"
RUN cd /tmp \
    && curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php



# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql


# Update package manager and install useful tools
RUN apt-get update && apt-get -y install apt-utils nano wget dialog vim


# Install important libraries
RUN echo "Install important libraries"
RUN apt-get -y install --fix-missing \
    cron \
    build-essential \
    git \
    curl \
    libcurl4 \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libzip-dev \
    zip \
    libbz2-dev \
    locales \
    libmcrypt-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev



RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN pecl install mongodb \
    && rm -rf /tmp/pear \
    && echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini


WORKDIR /var/www

COPY . /var/www/





# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN composer install



# RUN composer install
# Expose the port
EXPOSE 9000

# RUN ["chmod", "+x", "bin/entrypoint.sh"]
# Define the entry point
# CMD ["composer", "install", "&&", "php-fpm" ]

ENTRYPOINT ["sh", "bin/entrypoint.sh"]
