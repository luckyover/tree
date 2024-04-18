FROM php:8.1-fpm
ENV ACCEPT_EULA=Y

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
	libzip-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
	libonig-dev \
    nano \
    # Clear cache
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    # Microsoft SQL Server Prerequisites
    && apt-get update \
	&& apt-get install -y gnupg2 \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/ubuntu/20.04/prod.list \
        > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get install -y --no-install-recommends \
        locales \
        apt-transport-https \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen \
    && apt-get update \
    && apt-get -y --no-install-recommends install \
        unixodbc-dev \
        msodbcsql18 \
    # Install extensions
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl fileinfo \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd \
    # Install extensions for sql server
    && apt-get update \
    && apt-get -y --no-install-recommends install \
        libxml2-dev \
    && docker-php-ext-install pdo soap \
    && pecl install sqlsrv pdo_sqlsrv xdebug \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv xdebug \
    # Install composer
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && mkdir -p vendor \
    && chmod 777 -R ./ \
    # Add user for laravel application
    && groupadd -g 1000 www \
    && useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY ./API /var/www

# Copy existing application directory permissions
COPY --chown=www:www ./API /var/www

# Change current user to www
USER www

RUN composer install && php artisan key:generate

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
