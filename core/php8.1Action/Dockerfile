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

# build go proxy from source
FROM golang:1.18 AS builder_source
ARG GO_PROXY_GITHUB_USER=apache
ARG GO_PROXY_GITHUB_BRANCH=master
RUN git clone --branch ${GO_PROXY_GITHUB_BRANCH} \
   https://github.com/${GO_PROXY_GITHUB_USER}/openwhisk-runtime-go /src ;\
   cd /src ; env GO111MODULE=on CGO_ENABLED=0 go build main/proxy.go && \
   mv proxy /bin/proxy

# or build it from a release
FROM golang:1.18 AS builder_release
ARG GO_PROXY_RELEASE_VERSION=1.18@1.20.0
RUN curl -sL \
  https://github.com/apache/openwhisk-runtime-go/archive/{$GO_PROXY_RELEASE_VERSION}.tar.gz\
  | tar xzf -\
  && cd openwhisk-runtime-go-*/main\
  && GO111MODULE=on CGO_ENABLED=0 go build -o /bin/proxy

FROM php:8.1-cli-bullseye

# select the builder to use
ARG GO_PROXY_BUILD_FROM=release

# install PHP extensions
RUN apt-get -y update \
    # Upgrade installed packages to get latest security fixes if the base image does not contain them already.
    && apt-get upgrade -y --no-install-recommends \
    && apt-get -y install --no-install-recommends \
      unzip \
      libfreetype6 \
      libicu67 \
      libjpeg62-turbo \
      libpng16-16 \
      libssl1.1 \
      libxml2 \
      libzip4 \
      libpq5 \
      zip \
      libfreetype6-dev \
      libicu-dev \
      libjpeg-dev \
      libpng-dev \
      libssl-dev \
      libxml2-dev \
      libzip-dev \
      postgresql-server-dev-13 \
    \
    && docker-php-ext-install \
      bcmath \
      gd \
      intl \
      mysqli \
      opcache \
      pdo_mysql \
      pdo_pgsql \
      soap \
      zip \
    \
    && mkdir -p /usr/src/php/ext/mongodb \
    && curl -fsSL https://pecl.php.net/get/mongodb-1.14.0 | tar xvz -C "/usr/src/php/ext/mongodb" --strip 1 \
    && docker-php-ext-install -j$(nproc) mongodb \
    \
    && apt-get purge -y --auto-remove $PHPIZE_DEPS \
    && apt-get purge -y --auto-remove libfreetype6-dev \
      libicu-dev \
      libjpeg-dev \
      libpng-dev \
      libssl-dev \
      libxml2-dev \
      libzip-dev \
      postgresql-server-dev-13 \
    && apt-get autoremove -y \
    && apt-get clean -y \
    && rm -rf /usr/src/php

COPY php.ini /usr/local/etc/php

# install composer
RUN curl -s -f -L -o /tmp/installer.php https://getcomposer.org/installer \
    && php /tmp/installer.php --no-ansi --install-dir=/usr/bin --filename=composer \
    && composer --ansi --version --no-interaction --no-plugins --no-scripts

# install default Composer dependencies
RUN mkdir -p /phpAction/composer
COPY composer.json /phpAction/composer
RUN cd /phpAction/composer && /usr/bin/composer install --no-plugins --no-scripts --prefer-dist --no-dev -o && rm composer.lock

# install proxy binary along with compile and launcher scripts
RUN mkdir -p /phpAction/action
WORKDIR /phpAction
COPY --from=builder_source /bin/proxy /bin/proxy_source
COPY --from=builder_release /bin/proxy /bin/proxy_release
RUN mv /bin/proxy_${GO_PROXY_BUILD_FROM} /bin/proxy
ADD compile.php /bin/compile.php
ADD runner.php /bin/runner.php
ENV OW_COMPILER=/bin/compile.php

ENTRYPOINT [ "/bin/proxy" ]
