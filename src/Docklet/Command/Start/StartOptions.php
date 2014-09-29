<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Start;
use Docklet\Container\Config;


/**
 * Class StartOptions
 *
 * @package Docklet\Command\Start
 * @method StartOptions containerId(string $id)
 * @method StartOptions hostConfig(Config $config)
 * @method StartOptions name(string $name)
 */
class StartOptions
{
    public $containerId = '';
    public $hostConfig = null;
    public $name = '';

    public function __call($method, $params)
    {
        if (! property_exists($this, $method)) {
            throw new \Exception("Method $method() does not exist");
        }

        $this->$method = $params[0];
        return $this;
    }
}

