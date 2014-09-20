<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Container\Hydrator;


use Zend\Stdlib\Hydrator\AbstractHydrator;

class HostConfigHydrator extends AbstractHydrator
{
    protected $template = <<<JSON
    {
        "HostConfig": {
            "Binds": null,
            "ContainerIDFile": "",
            "LxcConf": [],
            "Privileged": false,
            "PortBindings": {},
            "Links": [],
            "PublishAllPorts": false,
            "CapAdd": [],
            "CapDrop": []
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
        $stdObj->ContainerIdFile = $hostConfig->getContainerIdFile();
        $stdObj->LxcConf = $hostConfig->getLxcConf();
        $stdObj->Privileged = $hostConfig->getPrivileged();
        $stdObj->PortBindings = $hostConfig->getPortBindings();
        $stdObj->PublisAllPorts = $hostConfig->getPublishAll();
        $stdObj->CapAdd = $hostConfig->getCapAdd();
        $stdObj->CapDrop = $hostConfig->getCapDrop();

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
