<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Container\Hydrator;


use Docklet\Container\Port;
use Docklet\Container\HostConfig;
use Zend\Stdlib\Hydrator\AbstractHydrator;

class HostConfigHydrator extends AbstractHydrator
{
    protected $template = <<<JSON
    {
        "Binds": null,
        "ContainerIDFile": "",
        "LxcConf": [],
        "Privileged": false,
        "PortBindings": {},
        "Links": null,
        "PublishAllPorts": false,
        "Dns": null,
        "DnsSearch": null,
        "VolumesFrom": null,
        "Devices": [],
        "NetworkMode": "bridge",
        "CapAdd": null,
        "CapDrop": null,
        "RestartPolicy": {
            "Name": "",
            "MaximumRetryCount": 0
        }
    }
JSON;

    /**
     * Extract values from a host config.
     *
     * @param  object $hostConfig
     *
     * @return array
     */
    public function extract($hostConfig)
    {
        /** @var HostConfig $hostConfig */
        $hostConfig = $hostConfig;

        $stdObj = json_decode($this->template);

        $stdObj->Binds = $hostConfig->getBinds();
        $stdObj->ContainerIDFile = $hostConfig->getContainerIdFile();
        $stdObj->LxcConf = $hostConfig->getLxcConf();
        $stdObj->Privileged = $hostConfig->getPrivileged();
        $stdObj->PortBindings = $hostConfig->getPortBindings();
        $stdObj->PublishAllPorts = $hostConfig->getPublishAll();

        if (count($hostConfig->getCapAdd())) {
            $stdObj->CapAdd = $hostConfig->getCapAdd();
        }

        if (count($hostConfig->getCapDrop())) {
            $stdObj->CapDrop = $hostConfig->getCapDrop();
        }

        $stdObj->PortBindings = array();
        /** @var Port $port */
        foreach ($hostConfig->getPortBindings() as $port) {
            $stdObj->PortBindings= array_merge(
                $stdObj->PortBindings,
                $port->toSpec()
            );
        }

        return (array) $stdObj;
    }

    /**
     * Hydrate the container host config with the provided $data. This method
     * has no internal implementation and will return the host config unmodified.
     *
     * @param  array  $data
     * @param  object $hostConfig
     *
     * @return object
     */
    public function hydrate(array $data, $hostConfig)
    {
        // TODO: Implement hydrate() method.
        return $hostConfig;
    }
}
