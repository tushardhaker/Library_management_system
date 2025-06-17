# Use the official PHP image with Apache
FROM php:8.1-apache

# Copy all project files to the web server root
COPY . /var/www/html/

# Install MySQLi extension
RUN docker-php-ext-install mysqli

# Set file permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 (HTTP)
EXPOSE 80
