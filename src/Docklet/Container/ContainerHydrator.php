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

class ContainerHydrator extends AbstractHydrator
{
    protected $template = <<<JSON
    {
         "Id": "",
         "Created": "",
         "Path": "",
         "Args": [],
         "Config": {},
         "State": {},
         "Image": "",
         "NetworkSettings": {},
         "SysInitPath": "",
         "ResolvConfPath": "",
         "Volumes": {},
         "HostConfig": {}
    }
JSON;

    /**
     * Extract values from a container.
     *
     * @param  object $container
     *
     * @return array
     */
    public function extract($container)
    {
        /** @var Container $container */
        $container = $container;

        $stdobj = json_decode($this->template);
        $stdobj->Id = $container->getId();
        $stdobj->Config = $container->getConfig()->toArray();
        return (array) $stdobj;
    }

    /**
     * Hydrate the docker container with the provided $data. This method
     * has no internal implementation and will return the container unmodified.
     *
     * @param  array  $data
     * @param  object $container
     *
     * @return object
     */
    public function hydrate(array $data, $container)
    {
        // TODO: Implement hydrate() method.
        return $container;
    }
}
