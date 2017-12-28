FROM ubuntu:14.04

RUN apt-get update && apt-get install -y \
  php5-common \
  php5-cli \
  php5-mysql \
  php5-fpm \ 
  php5-gd \ 
  php5-cgi \
  php5-imagick \
  php5-curl \ 
  php5-json \
  php5-xdebug \
  php5-dev \
  php-pear \
  php5-mcrypt \
  mariadb-client-5.5 \
  libcurl3-openssl-dev \
  openssh-server \
  curl \
  wget \
  vim \
  supervisor

RUN  php5enmod mcrypt

COPY php.ini /etc/php5/cgi/php.ini
COPY php-fpm.conf /etc/php5/fpm/php-fpm.conf

RUN apt-get clean

RUN echo "xdebug.remote_enable=On" >> /etc/php5/fpm/conf.d/20-xdebug.ini && \
    echo "xdebug.remote_port=9001" >> /etc/php5/fpm/conf.d/20-xdebug.ini && \
    echo "xdebug.remote_connect_back=On" >> /etc/php5/fpm/conf.d/20-xdebug.ini && \
    echo "xdebug.max_nesting_level=256" >> /etc/php5/fpm/conf.d/20-xdebug.ini

# Setup SSH.
# RUN mkdir /var/run/sshd && mkdir /root/.ssh
# RUN echo 'root:root' | chpasswd
# RUN sed -i 's/PermitRootLogin without-password/PermitRootLogin yes/' /etc/ssh/sshd_config
# COPY id_rsa.pub /root/.ssh/authorized_keys

# SSH login fix. Otherwise user is kicked off after login
RUN sed 's@session\s*required\s*pam_loginuid.so@session optional pam_loginuid.so@g' -i /etc/pam.d/sshd

ENV NOTVISIBLE "in users profile"
RUN echo "export VISIBLE=now" >> /etc/profile

# Configure supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Install Drush 7.
# RUN composer global require drush/drush:7.* && \
#     composer global update && \
# Unfortunately, adding the composer vendor dir to the PATH doesn't seem to work. So:
#   ln -s /root/.composer/vendor/bin/drush /usr/local/bin/drush

# Download latest stable release using the code below or browse to github.com/drush-ops/drush/releases.
RUN wget http://files.drush.org/drush.phar
# Or use our upcoming release: wget http://files.drush.org/drush-unstable.phar  
# Test your install.
RUN php drush.phar core-status
# Rename to `drush` instead of `php drush.phar`. Destination can be anywhere on $PATH. 
RUN chmod +x drush.phar
RUN mv drush.phar /usr/local/bin/drush

RUN usermod -u 1000 www-data
CMD ["/usr/bin/supervisord"]

WORKDIR '/var/www/html'

EXPOSE 9000 9001 22
