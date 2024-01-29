#!/usr/bin/env php
<?php
/*
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

/**
 * compile
 *
 * This file is launched by the action proxy.
 * It copies runner.php to right source directory and creates a bash exec script
 * that the action proxy will call to start everything off
 */

main($argc, $argv);
exit;

function main($argc, $argv)
{
    if ($argc < 4) {
        print("usage: <main-function-name> <source-dir> <bin-dir>");
        exit(1);
    }
    $main = $argv[1];
    $src = realpath($argv[2]);
    $bin = realpath($argv[3]);

    $shim = $bin.'/exec';

    sources($src);
    build($shim, $src, $main);
}

/**
 * Sort out the source code
 *
 * 1. Copy src/exec to src/index.php if necessary
 * 2. Ensure vendor directory exists
 */
function sources(string $src)
{
    // If the file uploaded by the user is a plain PHP file, then
    // the filename will be called exec by the action proxy.
    // Rename it to index.php
    if (file_exists($src . '/exec')) {
        rename($src . '/exec', $src . '/index.php');
    }

    // put vendor in the right place if it doesn't exist
    if (!is_dir($src . '/vendor')) {
        exec('cp -a /phpAction/composer/vendor ' . escapeshellarg($src . '/vendor'));
    }
}

/**
 * Create bin/exec shim
 */
function build(string $shim, string $src, string $main) : void
{
    $contents = <<<EOT
#!/bin/bash
cd $src
exec php -f /bin/runner.php -- "$main"

EOT;

    file_put_contents($shim, $contents);
    chmod($shim, 0755);
}
