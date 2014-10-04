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

use Docklet\Command\Run\Run;
use DockletTest\DockerTestCase;

class PsTest extends DockerTestCase
{
    protected static $image = 'slopjong/apache';
    protected static $container_command = array(
        '/usr/sbin/apache2ctl',
        '-D',
        'FOREGROUND',
    );

    public function testPs()
    {
        $options = Run::options(static::$image, static::$container_command)
            ->daemon(true);

        $json = static::$docker->run($options);
        $containerPsData = json_decode($json);
        $containerId = $containerPsData->Id;
        static::$removeContainersWithId[] = $containerId;

        $json = static::$docker->ps();
        $arr = json_decode($json, true);

        $this->assertNotEmpty($arr);

        $containerPsData = null;
        foreach ($arr as $psData) {
            if ($psData['Id'] === $containerId) {
                $containerPsData = $psData;
            }
        }

        $this->assertNotNull($containerPsData);

        // The docker API is inconsistent and not well-documented. The
        // documentation doesn't reflect the actual API information exchange.
        // In the following we test if the data structure has changed in
        // the meantime. This is done so that we keep track of API changes
        // which the documentation might not tell us.
        $arePresent = array_keys($containerPsData);

        // they don't need to be in the order as defined in the hydrator
        $shouldBePresent = array(
            'Command', 'Created', 'Id', 'Image', 'Names', 'Ports', 'Status'
        );

        $diff = array_merge(
            array_diff($shouldBePresent, $arePresent), // entries which are in $should but not in $are
            array_diff($arePresent, $shouldBePresent)  // entries which are in $are but not in $should
        );

        $this->assertEmpty($diff,
            "The docker API has changed. JSON key missing or key found which hasn't existed yet: " . join(', ', $diff));
    }
}
