FROM php:8.2.0-apache

WORKDIR /var/www/html

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install required dependencies, including PHP zip extension and libzip-dev
RUN apt-get update -y && apt-get install -y \
    libicu-dev \
    libmariadb-dev \
    unzip zip \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    curl \
    gnupg2 \
    lsb-release \
    ca-certificates \
    libzip-dev && \
    docker-php-ext-install gettext intl pdo_mysql && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd zip && \
    apt-get clean

# Add Composer to the container from the official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure Apache VirtualHost
RUN echo "<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>\n\
    ErrorLog \${APACHE_LOG_DIR}/error.log\n\
    CustomLog \${APACHE_LOG_DIR}/access.log combined\n\
    </VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# Copy application code to the container
COPY . /var/www/html

# Set proper ownership and permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Install Node.js and build frontend assets
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install && npm run build

# Expose port 80 for the web server
EXPOSE 80
