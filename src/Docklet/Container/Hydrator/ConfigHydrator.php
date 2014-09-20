<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Container\Hydrator;


use Docklet\Container\Config;
use Zend\Stdlib\Hydrator\AbstractHydrator;

class ConfigHydrator extends AbstractHydrator
{
    protected $template = <<<JSON
{
    "Hostname": "",
    "Domainname": "",
    "User": "",
    "Memory": 0,
    "MemorySwap": 0,
    "CpuShares": 0,
    "Cpuset": "",
    "AttachStdin": false,
    "AttachStdout": false,
    "AttachStderr": false,
    "PortSpecs": null,
    "ExposedPorts": {},
    "Tty": false,
    "OpenStdin": false,
    "StdinOnce": false,
    "Env": [],
    "Cmd": [""],
    "Image": "",
    "Volumes": {},
    "WorkingDir": "",
    "Entrypoint": null,
    "NetworkDisabled": false,
    "OnBuild": null
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

        $stdObj = json_decode($this->template);
        $stdObj->Hostname = $config->getHost();
        $stdObj->Image = $config->getImage();
        $stdObj->Tty = $config->getTtyMode();
        $stdObj->Cmd = $config->getCommands();
        $stdObj->ExposedPorts = $config->getExposedPorts();
        $stdObj->AttachStdout = $config->getAttachStdOut();
        $stdObj->AttachStderr = $config->getAttachStdErr();
        $stdObj->Env = $config->getEnvironmentVars();

        return (array) $stdObj;
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
