FROM nginx:mainline-alpine3.20-slim

WORKDIR /var/www/html

# Create PHP-FPM's and nginx user
RUN addgroup \
    --gid 1010 \
    www
RUN adduser \
    --disabled-password \
    --gecos "" \
    --ingroup www \
    --no-create-home \
    --uid 1010 \
    www

# Install the PHP production configuration
COPY .docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY .docker/nginx/nginx.conf /etc/nginx/nginx.conf
