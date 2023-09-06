<!--
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
-->

## Apache 1.19.0
  - No change

## Apache 1.18.0
  - Use php:8.0-cli-buster image to always pull latest patch version
  - Golang Action loop updatetd to golang 1.20
  - Support array result include sequence action (#120)

## Apache 1.17.0
  - Update version of PHP to 8.0.8
  - Build actionloop from 1.16@1.18.0 (#107)
  - Resolve akka versions explicitly. (#105, #104)

## Apache 1.16.0
Initial release

- Added: PHP: 8.0.2
- Used openwhisk-runtime-go 1.17.0 to build proxy
- Added: PHP extensions in addition to the standard ones:
    - bcmath
    - curl
    - gd
    - intl
    - mbstring
    - mysqli
    - pdo_mysql
    - pdo_pgsql
    - pdo_sqlite
    - soap
    - zip
    - mongo
- Added: Composer packages:
    - [guzzlehttp/guzzle](https://packagist.org/packages/guzzlehttp/guzzle): 7.2.0
    - [ramsey/uuid](https://packagist.org/packages/ramsey/uuid): 4.1.1
