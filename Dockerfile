# Use an official PHP image with Apache as the base image
FROM php:8.0-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the local directory contents (your PHP and HTML files) into the container
COPY . /var/www/html/

# Enable mod_rewrite (if needed for your site)
RUN a2enmod rewrite

# Install additional PHP extensions (if needed)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Expose port 80 (the default HTTP port)
EXPOSE 80

# Start Apache in the foreground (this will keep the container running)
CMD ["apache2-foreground"]
