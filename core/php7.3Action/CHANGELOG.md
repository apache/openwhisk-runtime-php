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

## Next Release
  - Update version of PHP to 7.3.33
  - Update to Debian "buster"

## Apache 1.17.0
  - Update version of PHP to 7.3.29
  - Build actionloop from 1.16@1.18.0 (#107)
  - Resolve akka versions explicitly. (#105, #104)

## Apache 1.16.0
  - Update version of PHP to 7.3.27
  - Use openwhisk-runtime-go 1.17.0 to build proxy
  - Update guzzlehttp/guzzle to 6.5.5
  - Update ramsey/uuid to 3.9.3

## Apache 1.15.0
  - Update version of PHP to 7.3.22
  - Use golang 1.15 and openwhisk-runtime-go 1.16.0 to build proxy

## Apache 1.14.0
Changes:
  - Update version of PHP to 7.3.12
  - Update guzzlehttp/guzzle to 6.5.0
  - Update ramsey/uuid to 3.9.1
  - Added PHP extension mongodb
  - Support getenv()
  - Support for __OW_ACTION_VERSION (openwhisk/4761)

## Apache 1.13.0-incubating
Initial release

- Added: PHP: 7.3.3
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
- Added: Composer packages:
    - [guzzlehttp/guzzle](https://packagist.org/packages/guzzlehttp/guzzle): 6.3.3
    - [ramsey/uuid](https://packagist.org/packages/ramsey/uuid): 3.8.0
