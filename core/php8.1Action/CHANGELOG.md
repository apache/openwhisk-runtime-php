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

## Migrating from PHP 7 to PHP 8
PHP 8 as new features and some incompatiblities. See [migration guide](https://www.php.net/manual/en/migration80.php) for more information.

## Next Release
- Used openwhisk-runtime-go 1.22.0 to build proxy

## Initial release

- Added: PHP: 8.1.9
- Used openwhisk-runtime-go 1.18.0 to build proxy
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
    - [guzzlehttp/guzzle](https://packagist.org/packages/guzzlehttp/guzzle): 7.4.5
    - [ramsey/uuid](https://packagist.org/packages/ramsey/uuid): 4.4.0
