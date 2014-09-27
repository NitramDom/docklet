<?php

include('../vendor/autoload.php');

use Docklet\Docker;
use Docklet\Command\Run\Run;
use Docklet\Container\Container;

// 1. Create a new Docker client
//$docker = new Docker('unix:///var/run/docker.sock');
$docker = new Docker('tcp://127.0.0.1:9999');

// 2. Create the config for the run command
$command = array(
    '/usr/sbin/apache2ctl',
    '-D',
    'FOREGROUND',
);

$options = Run::options('slopjong/apache', $command)
    ->portBinding('80', '8999')
    ->daemon(true)
    ->returnContainer(true);

// 3. Run a container and point your browser to http://localhost:8999
/** @var Container $container */
$container = $docker->run($options);

// 4. Output the container ID
echo $container->getId();

// 5. Stop the container after 10 seconds
$docker->stop($container->getId(), 10);
