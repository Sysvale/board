FROM ubuntu:20.04

LABEL author="Elayne Lemos elayne.lemos@sysvale.com"

# Image ENV variables
ENV PATH ${PATH}:/usr/local/bin:/usr/local/sbin:/usr/bin:/sbin:/bin:/usr/sbin
ENV PROJECT_FOLDER /home/project-folder
ENV INFO_IMAGE "RUN apachelinker <$PROJECT_PUBLIC_FOLDER> to start the apache server"
ENV SEPARATOR -
ENV TZ=America/Recife
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Copy apache config
COPY conf/apache.conf conf/.bashrc /tmp/

# Install basic packages
RUN apt-get update \
  && apt-get install -y --no-install-recommends apt-utils \
  && apt-get install -y --no-install-recommends software-properties-common \
  && LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php \
  && apt-get update \
  && apt-get purge -y software-properties-common \
  && apt-get install -y --no-install-recommends openssl \
    bash-completion \
    ca-certificates \
    zip \
    unzip \
    curl \
    sudo \
    wget \
    make \
    htop \
    git \
    vim \
    gcc \
    libxrender1 \
    libfontconfig \
    libxml2 \
    libicu66 \
    apache2 \
    php7.4 \
    php7.4-mbstring \
    php7.4-xml \
    php7.4-curl \
    php7.4-imagick \
    php7.4-zip \
    php7.4-gd \
    php7.4-bcmath \
    php7.4-json \
    php7.4-sockets \
    php7.4-tokenizer \
    php7.4-mongodb \
    php7.4-redis \
  && a2enmod rewrite \
  && mv /tmp/apache.conf /etc/apache2/sites-enabled/000-default.conf \
  && mv /tmp/.bashrc /root/.bashrc \
  && apt-get autoremove -y \
  && apt-get clean \
  && apt-get autoclean \
  && rm -rf /var/lib/apt/lists/*

#Install Composer 2.1.5
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php composer-setup.php \
    --install-dir=/usr/local/bin \
    --filename=composer \
    --version=2.1.5 \
  && php -r "unlink('composer-setup.php');"

#Install Node e NPM
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - \
  && apt-get update \
  && apt-get --no-install-recommends install -y nodejs \
  && npm install -g npm n cross-env \
  && n 12.22 \
  && apt-get purge -y make \
  && apt-get autoremove -y \
  && apt-get clean \
  && apt-get autoclean \
  && rm -rf /var/lib/apt/lists/*

# Create www-data user and group
RUN usermod --non-unique --uid 1000 www-data \
  && groupmod --non-unique --gid 1000 www-data \
  && chown -R www-data:www-data /var/www

WORKDIR /
