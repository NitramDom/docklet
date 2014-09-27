<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Run;


use Docklet\Container\Port;

/**
 * <strong>The</strong> run options.
 *
 * @package Docklet\Command
 *
 * @method RunOptions daemon(boolean $daemon)
 * @method RunOptions ttyMode(boolean $mode)
 * @method RunOptions image(string $image)
 * @method RunOptions command(mixed $command)
 * @method RunOptions volumes(array $volumes)
 * @method RunOptions returnContainer(boolean $flag)
 */
class RunOptions
{
    public $daemon = false;
    public $ttyMode = false;
    public $interactive = false;
    public $image = '';
    public $command = '';
    public $environmentVariables = array();
    public $volumes = array();
    public $portBindings = array();
    public $returnContainer = false;

    public function __call($method, $params)
    {
        if (! property_exists($this, $method)) {
            throw new \Exception("Method $method() does not exist");
        }

        $this->$method = $params[0];
        return $this;
    }

    /**
     * @param $containerPort
     * @param $hostPort
     * @param $hostIp
     * @param $protocol
     *
     * @return $this
     */
    public function portBinding($containerPort, $hostPort, $hostIp = '', $protocol = 'tcp')
    {
        if (!$hostPort && !$hostIp) {
            // @todo throw an exception
        }

        $host = "$hostPort:";
        if ($hostIp) {
            $host = $hostIp . ':' . $host;
        }

        // [[hostIp:][hostPort]:]port[/protocol]
        $this->portBindings[] = new Port($host . "$containerPort/$protocol");

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function environmentVariable($name, $value)
    {
        $this->environmentVariables[] = "$name=$value";
        return $this;
    }
}
