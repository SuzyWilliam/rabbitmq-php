FROM php:8.2-rc-fpm-alpine

# Use the default production configuration
# RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY ./php.ini-development /usr/local/etc/php/php.ini

RUN docker-php-ext-install bcmath 
# CMD [ "cd", "../../" ]
# CMD [ "ls", "-a" ]
# WORKDIR /src
# RUN ls -a
COPY src /var/www/html

# RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
#     php composer-setup.php && \
#     php -r "unlink('composer-setup.php');" && \
#     mv composer.phar /usr/local/bin/composer

RUN curl -Ls https://getcomposer.org/composer.phar > /usr/local/bin/composer && chmod +x /usr/local/bin/composer

CMD [ "composer", "install" ]