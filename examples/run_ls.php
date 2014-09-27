<?php

include('../vendor/autoload.php');

use Docklet\Docker;
use Docklet\Command\Run\Run;

// 1. Create a new Docker client
//$docker = new Docker('unix:///var/run/docker.sock');
$docker = new Docker('tcp://127.0.0.1:9999');

// 2. Create the config for the run command
$options = Run::options('slopjong/apache:latest', 'ls');

// 3. Run a container
$json = $docker->run($options);

// 4. Output the result(s)
$stdObj = json_decode($json);

if (count($stdObj->LastCmdOutput)) {
    foreach ($stdObj->LastCmdOutput as $output) {
        echo $output;
    }
}
