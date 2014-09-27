<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Restart;


/**
 * <strong>The</strong> restart options.
 *
 * @package Docklet\Command
 *
 * @method RestartOptions containerId(string $id)
 * @method RestartOptions wait(int $seconds)
 */
class RestartOptions
{
    public $containerId = '';
    public $wait = 10;

    public function __call($method, $params)
    {
        if (! property_exists($this, $method)) {
            throw new \Exception("Method $method() does not exist");
        }

        $this->$method = $params[0];
        return $this;
    }
}
