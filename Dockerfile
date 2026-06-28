FROM caddy:2-alpine AS caddy

FROM php:7.4-fpm-alpine

RUN apk add --no-cache ca-certificates tzdata \
    && docker-php-ext-install pdo_mysql

COPY --from=caddy /usr/bin/caddy /usr/bin/caddy
COPY docker/Caddyfile /etc/caddy/Caddyfile
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
COPY . /var/www/html

WORKDIR /var/www/html

RUN chmod +x /usr/local/bin/docker-entrypoint.sh \
    && rm -rf /var/www/html/docker \
    && chown -R www-data:www-data /var/www/html

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
