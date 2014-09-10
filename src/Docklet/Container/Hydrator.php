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

class Hydrator extends AbstractHydrator
{
    /**
     * Extract values from a container.
     *
     * @param  object $container
     *
     * @return array
     */
    public function extract($container)
    {
        // TODO: Implement extract() method.
        return array();
    }

    /**
     * Hydrate the docker container with the provided $data.
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
