FROM php:8.4-cli

WORKDIR /app

# Install dependency
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip nodejs npm \
    && docker-php-ext-install zip

# Copy project
COPY . .

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# 🔥 Install & build Vite
RUN npm install
RUN npm run build

# Run Laravel
CMD php artisan serve --host=0.0.0.0 --port=$PORT