FROM yiisoftware/yii2-php:7.4-apache
WORKDIR /app
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer install --prefer-dist

# Change document root for Apache
RUN sed -i -e 's|/app/web|/app/backend/web|g' /etc/apache2/sites-available/000-default.conf

 # - echo "test"
    #  - /usr/local/bin/php composer.phar install
    #  - /usr/local/bin/php init --env="Development [Docker Compose]" --overwrite=All 
    #  - php yii migrate --interactive=0
    #  - php yii fixture/load "*" --interactive=0
