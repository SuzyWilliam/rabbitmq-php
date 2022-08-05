FROM php:8.0-rc-apache-buster

RUN apt-get -y update
RUN apt-get -y install git

# Use the default production configuration
# RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
# COPY ./php.ini-development /usr/local/etc/php/php.ini

RUN docker-php-ext-install bcmath sockets
# CMD [ "cd", "../../" ]
# CMD [ "ls", "-a" ]
# WORKDIR /src
# RUN ls -a
RUN mkdir /var/www/html/rabbitmq-example
COPY ../../src /var/www/html/rabbitmq-example

# RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
#     php composer-setup.php && \
#     php -r "unlink('composer-setup.php');" && \
#     mv composer.phar /usr/local/bin/composer

# install composer 
RUN curl -Ls https://getcomposer.org/composer.phar > /usr/local/bin/composer && chmod +x /usr/local/bin/composer
RUN chown -R www-data:www-data /var/www

WORKDIR /var/www/html/rabbitmq-example
RUN composer install