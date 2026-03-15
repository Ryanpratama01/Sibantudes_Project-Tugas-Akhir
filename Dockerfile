FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip intl gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . /app

RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN npm install
RUN npm run build

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}