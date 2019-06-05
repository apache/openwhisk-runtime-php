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

FROM php:7.1.30-alpine

RUN \
    apk update && apk upgrade && \
    # install dependencies
   apk add \
       postgresql-dev \
       icu \
       icu-libs \
       icu-dev \
       freetype-dev \
       libjpeg-turbo-dev \
       libpng-dev \
       libxml2-dev \
   && \
   # install useful PHP extensions
   docker-php-ext-install \
       opcache \
       mysqli \
       pdo_mysql \
       pdo_pgsql \
       intl \
       bcmath \
       zip \
       gd \
       soap

# install composer
RUN curl -s -f -L -o /tmp/installer.php https://getcomposer.org/installer \
    && php /tmp/installer.php --no-ansi --install-dir=/usr/bin --filename=composer \
    && composer --ansi --version --no-interaction

# create src directory to store action files
RUN mkdir -p /action/src

# install Composer dependencies
COPY composer.json /action
RUN cd /action && /usr/bin/composer install --no-plugins --no-scripts --prefer-dist --no-dev -o && rm composer.lock

# copy required files
COPY router.php /action
COPY runner.php /action

# Run webserver on port 8080

CMD [ "php", "-S", "0.0.0.0:8080", "-d", "expose_php=0", "-d", "html_errors=0", "-d", "error_reporting=E_ALL", "/action/router.php" ]
