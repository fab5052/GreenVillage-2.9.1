FROM php:8.2-apache

# Télécharger et configurer l'installateur d'extensions PHP
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

# Activer les modules Apache
RUN a2enmod \
    rewrite \
    expires \
    headers

# Installer des dépendances système
RUN apt-get update && apt-get install -y --no-install-recommends\
    acl \
    libicu-dev \
    libzip-dev \
    nano \
    wget \
    zip \
    git \
    curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP nécessaires
RUN install-php-extensions \
    pdo_mysql \
    mysqli \
    intl \
    apcu \
    xdebug 
    # opcache 


# Installer OPcache
RUN docker-php-ext-install opcache

# Copier un fichier de configuration personnalisée d'OPcache
# COPY ./docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
ADD opcache.ini $PHP_INI_DIR/conf.d/opcache.ini

# Install nvm
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.0/install.sh | bash \
    && export NVM_DIR="/root/.nvm" \
    && [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" \
    && nvm install 22

RUN wget https://getcomposer.org/installer -O /var/www/composer-setup.php
RUN php /var/www/composer-setup.php 
RUN mv composer.phar /usr/bin/composer 
RUN chmod +x /usr/bin/composer

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Configurer Xdebug
RUN echo 'xdebug.mode=develop,debug,coverage' >> /usr/local/etc/php/conf.d/xdebug.ini \
     && echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/xdebug.ini

# # # Copier et activer php.ini (ajustez si nécessaire)
# RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini \
#     && echo 'short_open_tag = Off' >> /usr/local/etc/php/php.ini

RUN apt-get update && apt-get install -y curl \
    && curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs

# # Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

COPY ./automation/docker/default-vhost.conf /etc/apache2/sites-available/default-vhost.conf

RUN a2dissite 000-default.conf \
    && a2ensite default-vhost.conf
    
COPY ./automation/scripts/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh


CMD ["/usr/local/bin/docker-entrypoint.sh"]

# Crée un utilisateur avec un UID/GID correspondant à celui de l'utilisateur hôte


RUN chmod -R 777 /var/www && chown -R www-data:www-data /var/www

RUN git config --global --add safe.directory '*' && \
    git config --global user.email "f.beaujois@gmail.com" && \
    git config --global user.name "fab5052" 
