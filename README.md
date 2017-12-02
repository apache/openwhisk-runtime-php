# Apache OpenWhisk runtimes for PHP
[![Build Status](https://travis-ci.org/apache/incubator-openwhisk-runtime-php.svg?branch=master)](https://travis-ci.org/apache/incubator-openwhisk-runtime-php)


### Give it a try today
To use as a docker action
```
wsk action update myAction myAction.php --docker openwhisk/action-php-v7.1:1.0.0
```
This works on any deployment of Apache OpenWhisk

### To use on deployment that contains the rutime as a kind
To use as a kind action
```
wsk action update myAction myAction.php --kind php:7.1
```

### Local development
```
./gradlew core:php7.1Action:distDocker
```
This will produce the image `whisk/action-php-v7.1`

Build and Push image
```
docker login
./gradlew core:php7.1Action:distDocker -PdockerImagePrefix=$prefix-user -PdockerRegistry=docker.io
```

Deploy OpenWhisk using ansible environment that contains the kind `php:7.1`
Assuming you have OpenWhisk already deploy localy and `OPENWHISK_HOME` pointing to root directory of OpenWhisk core repository.

Set `ROOTDIR` to the root directory of this repository.

Redeploy OpenWhisk
```
cd $OPENWHISK_HOME/ansible
ANSIBLE_CMD="ansible-playbook -i ${ROOTDIR}/ansible/environments/local"
$ANSIBLE_CMD setup.yml
$ANSIBLE_CMD couchdb.yml
$ANSIBLE_CMD initdb.yml
$ANSIBLE_CMD wipe.yml
$ANSIBLE_CMD openwhisk.yml
```

Or you can use `wskdev` and create a soft link to the target ansible environment, for example:
```
ln -s ${ROOTDIR}/ansible/environments/local ${OPENWHISK_HOME}/ansible/environments/local-php
wskdev fresh -t local-php
```

To use as docker action push to your own dockerhub account
```
docker tag whisk/php7.1Action $user_prefix/action-php-v7.1
docker push $user_prefix/action-php-v7.1
```
Then create the action using your the image from dockerhub
```
wsk action update myAction myAction.php --docker $user_prefix/action-php-v7.1
```
The `$user_prefix` is usually your dockerhub user id.



# License
[Apache 2.0](LICENSE.txt)


