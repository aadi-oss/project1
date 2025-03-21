FROM php:8.1-fpm

# Install necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Set working directory
WORKDIR /var/www/html

# Copy all project files to the container
COPY . .

# Expose port
EXPOSE 9000

CMD ["php-fpm"]
