FROM ghcr.io/roqmeu/roadrunner-docker:roadrunner-2024.3.1

COPY --chown=www-data:www-data ./composer.json ./composer.lock ./

RUN set -eux; \
    mkdir -p /opt/app/vendor; \
    composer install --prefer-dist --no-cache --no-progress --no-interaction; \
    composer check-platform-reqs; \
    chmod -R 755 /opt/app/vendor
