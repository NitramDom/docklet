<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Container;


use Zend\Stdlib\Hydrator\AbstractHydrator;

class ConfigHydrator extends AbstractHydrator
{
    protected $template = <<<JSON
{
    "Hostname":"",
    "User":"",
    "Memory":0,
    "MemorySwap":0,
    "AttachStdin":false,
    "AttachStdout":true,
    "AttachStderr":true,
    "PortSpecs":null,
    "Tty":false,
    "OpenStdin":false,
    "StdinOnce":false,
    "Env":null,
    "Cmd":[],
    "Image":"",
    "Volumes":{},
    "WorkingDir":"",
    "DisableNetwork": false,
    "ExposedPorts":{}
}
JSON;

    /**
     * Extract values from a config.
     *
     * @param  object $config
     *
     * @return array
     */
    public function extract($config)
    {
        /** @var Config $config */
        $config = $config;

        $stdobj = json_decode($this->template);
        $stdobj->HostName = $config->getHost();
        $stdobj->Image = $config->getImage();
        $stdobj->Tty = $config->getTtyMode();
        $stdobj->Cmd = $config->getCommands();
        return (array) $stdobj;
    }

    /**
     * Hydrate the docker container with the provided $data. This method
     * has no internal implementation and will return the container unmodified.
     *
     * @param  array  $data
     * @param  object $config
     *
     * @return object
     */
    public function hydrate(array $data, $config)
    {
        // TODO: Implement hydrate() method.
        return $config;
    }
}
