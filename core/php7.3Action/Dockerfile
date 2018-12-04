#
# Licensed to the Apache Software Foundation (ASF) under one or more
# contributor license agreements.  See the NOTICE file distributed with
# this work for additional information regarding copyright ownership.
# The ASF licenses this file to You under the Apache License, Version 2.0
# (the "License"); you may not use this file except in compliance with
# the License.  You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
#

FROM openwhisk/actionloop:410d006 as builder

FROM php:7.3.0RC6-cli-stretch

# install dependencies
RUN \
    apt-get -y update && \
    apt-get -y install \
      libfreetype6-dev \
      libicu-dev \
      libicu57 \
      libjpeg-dev \
      libpng-dev \
      libxml2-dev \
      libzip-dev \
      postgresql-server-dev-9.6 \
      unzip \
      zlib1g-dev

# Install useful PHP extensions
RUN \
    docker-php-ext-install \
      bcmath \
      gd \
      intl \
      mysqli \
      opcache \
      pdo_mysql \
      pdo_pgsql \
      soap \
      zip

COPY php.ini /usr/local/etc/php

# install composer
RUN curl -s -f -L -o /tmp/installer.php https://getcomposer.org/installer \
    && php /tmp/installer.php --no-ansi --install-dir=/usr/bin --filename=composer \
    && composer --ansi --version --no-interaction --no-plugins --no-scripts


# install default Composer dependencies
RUN mkdir -p /phpAction/composer
COPY composer.json /phpAction/composer
RUN cd /phpAction/composer && /usr/bin/composer install --no-plugins --no-scripts --prefer-dist --no-dev -o && rm composer.lock

# install proxy binary alogn with compile and launcher scripts
RUN mkdir -p /phpAction/action
WORKDIR /phpAction
COPY --from=builder /bin/proxy /bin/proxy
ADD compile /bin/compile
ADD runner.php /bin/runner.php
ENV OW_COMPILER=/bin/compile

ENTRYPOINT [ "/bin/proxy" ]
