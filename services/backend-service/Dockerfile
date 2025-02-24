FROM php:8.1-cli

WORKDIR /var/www/backend-service

COPY . .

RUN apt-get update && apt-get install -y unzip \
    && docker-php-ext-install pdo pdo_mysql

CMD ["php", "-S", "0.0.0.0:8082", "-t", "."]
