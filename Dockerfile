FROM php:8.2-apache
WORKDIR /var/www/html
COPY . .
# Optional: Composer deps
RUN apt-get update && apt-get install -y git unzip curl \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && if [ -f composer.json ]; then composer install --no-dev --prefer-dist --no-interaction; fi
# Useful Apache stuff
RUN a2enmod rewrite
EXPOSE 80
CMD ["apache2-foreground"]
