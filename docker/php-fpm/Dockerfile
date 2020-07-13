FROM phpdockerio/php74-fpm:latest
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install  redis-server php-memcached php7.4-mysql php-redis \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# # Configure Node related environment.
# # -----------------------------------

ENV NVM_DIR /usr/local/nvm
RUN mkdir $NVM_DIR


# # Install NVM.
# # -------------

RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.2/install.sh | bash \
    && [ -s "$NVM_DIR/nvm.sh" ] \
    && . "$NVM_DIR/nvm.sh"


# # Setup NVM.
# # ----------

RUN echo export NVM_DIR="/usr/local/nvm" '\n' "[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"" '\n' "[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"" \
 >> /${APP_NAME}/.bashrc


# # Make sure any sudoer can control libs.
# # --------------------------------------

RUN chmod a+w -R /usr/local


# # Install Node and Node global dependencies.
# # ---------------------------------

COPY ./.nvmrc /etc/.nvmrc
RUN . /usr/local/nvm/nvm.sh && \
    cd /etc/ && nvm install v12.14.0 && nvm alias default && nvm use default && \
    npm install -g yarn gulp-cli

# Install redis
COPY ./redis.conf /etc/redis/redis.conf