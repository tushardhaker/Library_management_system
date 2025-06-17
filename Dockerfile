# Use the official PHP Apache image
FROM php:8.1-apache

# Install MySQLi extension
RUN docker-php-ext-install mysqli

# Enable .htaccess by allowing overrides
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copy all project files to Apache server root
COPY . /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html/ && chmod -R 755 /var/www/html/

# Expose port 80
EXPOSE 80
