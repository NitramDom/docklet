<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace DockletTest\Command;


// the auto-loader is not configured for unit test cases, including manually here
include_once('DockletTest/DockerTestCase.php');

use Docker\Docker;
use Docklet\Command\Run\Run;
use DockletTest\DockerTestCase;

class RmTest extends DockerTestCase
{
    protected static $image = 'slopjong/apache';
    protected static $container_command = array(
        '/usr/sbin/apache2ctl',
        '-D',
        'FOREGROUND',
    );

    public function testStopContainerAndRmById()
    {
        $options = Run::options(static::$image, static::$container_command)
            ->daemon(true);

        $container = static::$docker->run($options);
        $stdObj = json_decode($container);
        $containerId = substr($stdObj->Id, 0, 12);

        static::$docker->stop($containerId);
        static::$docker->rm($containerId);

        $containers = static::dockerPs();
        $found = preg_match('/'. $containerId .'/m', $containers);
        $this->assertFalse(filter_var($found, FILTER_VALIDATE_BOOLEAN));

        static::$removeContainersWithId[] = $containerId;
    }

    public function testForceRmById()
    {
        $options = Run::options(static::$image, static::$container_command)
            ->daemon(true);

        $container = static::$docker->run($options);
        $stdObj = json_decode($container);
        $containerId = substr($stdObj->Id, 0, 12);

        static::$docker->rm($containerId, true);

        $containers = static::dockerPs();
        $found = preg_match('/'. $containerId .'/m', $containers);
        $this->assertFalse(filter_var($found, FILTER_VALIDATE_BOOLEAN));

        static::$removeContainersWithId[] = $containerId;
    }
}
