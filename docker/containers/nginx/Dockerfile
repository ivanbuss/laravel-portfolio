FROM ubuntu:14.04
MAINTAINER Sergey Ivin <phirames@yandex.ru>

RUN apt-get update && apt-get install -y nginx

# Set cope fil for SSL:
COPY ssl/cert.crt /etc/nginx/cert.crt
COPY ssl/cert.key /etc/nginx/cert.key

# Remove the default Nginx configuration file
RUN rm -v /etc/nginx/nginx.conf

# Copy a configuration file from the current directory
COPY nginx.conf /etc/nginx/

COPY laravel.conf /etc/nginx/sites-available/

RUN ln -s /etc/nginx/sites-available/laravel.conf /etc/nginx/sites-enabled/laravel
RUN rm /etc/nginx/sites-enabled/default

RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN usermod -u 1000 www-data

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
