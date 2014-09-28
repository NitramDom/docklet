<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace DockletTest;

use Docklet\Docker;

abstract class DockerTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var Docker */
    protected static $docker = null;
    protected static $removeContainersWithId = array();

    public static function setUpBeforeClass()
    {
        static::$docker = Docker::getInstance();
    }

    public static function tearDownAfterClass()
    {
        static::$docker = null;
        if (! empty(static::$removeContainersWithId)) {
            system("docker rm -f " . join(' ', static::$removeContainersWithId));
            static::$removeContainersWithId = array();
        }
    }

    /**
     * Get container information.
     *
     * @param boolean $runningOnly specifies whether to get information of running containers only
     * @return string
     */
    public static function dockerPs($runningOnly = false)
    {
        ob_start();
        passthru("docker ps" . ((!$runningOnly) ? " -a" : '' ));
        return ob_get_clean();
    }
}
