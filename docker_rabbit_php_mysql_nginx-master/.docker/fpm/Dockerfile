FROM php:7.4-fpm

ARG USER_ID
ARG GROUP_ID

# Download script to install PHP extensions and dependencies
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync

RUN DEBIAN_FRONTEND=noninteractive apt-get update -q \
    && DEBIAN_FRONTEND=noninteractive apt-get install -qq -y \
      curl \
      git \
      zip unzip \
      wget \
      vim \
      nano \
      webp \
      locales \
      ghostscript \
      libgs-dev \
      imagemagick \
    && install-php-extensions \
      calendar \
      exif \
      gd \
      amqp \
      gettext \
      igbinary \
      imagick \
      intl \
      mysqli \
      opcache \
      pdo_mysql \
      pdo_pgsql \
      pgsql \
      redis \
      shmop \
      sockets \
      sysvmsg \
      sysvsem \
      sysvshm \
      xsl \
      zip \
      xdebug

# Disable ghostscript in ImageMagick
RUN sed -i '/disable ghostscript format types/,+6d' /etc/ImageMagick-6/policy.xml

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.10.17

# PHP configuration
RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
ADD conf/php.local.ini /usr/local/etc/php/conf.d/php.local.ini
COPY conf/www.conf /usr/local/etc/php-fpm.d/www.conf

# Set crontab sourcing
RUN printenv | sed 's/^\(.*\)$/\1/g' > /etc/environment

# Enable environment variables in PHP FPM
RUN sed -i 's/;clear_env = no/clear_env = no/g' /usr/local/etc/php-fpm.d/www.conf
RUN printf "[www]\n" > /usr/local/etc/php-fpm.d/env.conf
RUN printenv | sed "s/\([^=]*\)=\(.*\)/env[\1] = '\2'/" | grep -v "= ''" >> /usr/local/etc/php-fpm.d/env.conf

# Set workdir
WORKDIR /var/www

# Disable CLI xdebug
RUN echo "XDEBUG_MODE=off" >> ~/.bashrc
RUN echo "XDEBUG_MODE=off" >> /var/www/.bashrc

# Add user id and group id to www-data
RUN usermod -u ${USER_ID} www-data
RUN groupmod -g ${GROUP_ID} www-data
USER "${USER_ID}:${GROUP_ID}"
