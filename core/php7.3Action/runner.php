<?php
/**
 * PHP Action runner
 *
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


// open fd/3 as that's where we send the result
$fd3 = fopen('php://fd/3', 'w');

// Register a shutdown function so that we can fail gracefully when a fatal error occurs
register_shutdown_function(function () use ($fd3) {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
        file_put_contents('php://stderr', "An error occurred running the action.\n");
        fwrite($fd3, "An error occurred running the action.\n");
    }
    fclose($fd3);
});

require 'vendor/autoload.php';
require 'index.php';

// retrieve main function
$__functionName = $argv[1] ?? 'main';


// read stdin
while ($f = fgets(STDIN)) {
    // call the function
    $data = json_decode($f ?? '', true);
    if (!is_array($data)) {
        $data = [];
    }

    // convert all parameters other than value to environment variables
    foreach ($data as $key => $value) {
        if ($key !== 'value') {
            $envKeyName = '__OW_' . strtoupper($key);
            $_ENV[$envKeyName] = $value;
            putenv($envKeyName . '=' . $value);
        }
    }

    $values = $data['value'] ?? [];
    try {
        $result = $__functionName($values);

        // convert result to an array if we can
        if (is_object($result)) {
            if (method_exists($result, 'getArrayCopy')) {
                $result = $result->getArrayCopy();
            } elseif ($result instanceof stdClass) {
                $result = (array)$result;
            }
        } elseif ($result === null) {
            $result = [];
        }

        // process the result
        if (!is_array($result)) {
            file_put_contents('php://stderr', 'Result must be an array but has type "'
                . gettype($result) . '": ' . (string)$result);
            file_put_contents('php://stdout', 'The action did not return a dictionary.');
            $result = (string)$result;
        } else {
            $result = json_encode((object)$result);
        }
    } catch (Throwable $e) {
        file_put_contents('php://stderr', (string)$e);
        $result = 'An error occurred running the action.';
    }

    // ensure that the sentinels will be on their own lines
    file_put_contents('php://stderr', "\n");
    file_put_contents('php://stdout', "\n");

    // cast result to an object for json_encode to ensure that an empty array becomes "{}" & send to fd/3
    fwrite($fd3, $result . "\n");
}
