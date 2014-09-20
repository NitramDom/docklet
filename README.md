Docklet
=======

A docker client for PHP.

```
<?php

include('../vendor/autoload.php');

use Docklet\Docker;
use Docklet\Container\Config;

// 1. Create a new Docker client
//$docker = new Docker('unix:///var/run/docker.sock');
$docker = new Docker('tcp://127.0.0.1:9999');

// 2. Create a config for the run command
$config = new Config(
    'slopjong/apache:latest',
    array('ls', '/etc')
);

// 3. Run a container
$json = $docker->run($config);

// 4. Output the result(s)
$stdObj = json_decode($json);

if (count($stdObj->LastCmdOutput)) {
    foreach ($stdObj->LastCmdOutput as $output) {
        echo $output;
    }
}
```