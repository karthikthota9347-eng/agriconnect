FROM php:8.2-apache

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite

# Install zip support
RUN apt-get update && apt-get install -y libzip-dev zip && docker-php-ext-install zip

# Copy project files
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R 755 /var/www/html/
RUN mkdir -p /var/www/html/assets/uploads && chmod 777 /var/www/html/assets/uploads

# Apache config
RUN echo '<Directory /var/www/html>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

EXPOSE 80
