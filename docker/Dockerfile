FROM composer AS build

# install
RUN mkdir -p /opt/php-mail-interface
COPY . /opt/php-mail-interface
RUN cd /opt/php-mail-interface && composer install --no-dev

# install setup stuff
RUN mkdir -p /opt/php-mail-interface/setup
COPY docker/php /opt/php-mail-interface/setup

# cleanup
RUN cd /opt/php-mail-interface && rm \
    composer.json \
    composer.lock && rm -rf \
    example \
    docker

FROM php:7.4-apache AS run

RUN mkdir -p /var/www/html/php-mail-interface
COPY --from=build /opt/php-mail-interface /var/www/html/php-mail-interface