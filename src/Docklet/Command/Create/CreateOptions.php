<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Create;


use Docklet\Container\Config;

/**
 * Create options. Not implementing an interface.
 *
 * @package Docklet\Command\Create
 * @method CreateOptions config(Config $config)
 */
class CreateOptions
{
    /**
     * @var Config
     */
    public $config = null;

    public function __construct($image, $command = '')
    {
        $this->config = new Config($image, $command);
    }

    public function __call($method, $params)
    {
        if (! property_exists($this, $method)) {
            throw new \Exception("Method $method() does not exist");
        }

        $this->$method = $params[0];
        return $this;
    }
}

