<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Container;


use Docklet\Container\Port;
use Docklet\Container\Hydrator\HostConfigHydrator;

class HostConfig
{
    protected $binds = null;
    protected $capAdd = array();
    protected $capDrop = array();
    protected $containerIdFile = '';
    protected $links = array();
    protected $lxcConf = array();
    protected $portBindings = null;
    protected $privileged = false;
    protected $publishAllPorts = false;

    public function getBinds()
    {
        return $this->binds;
    }

    public function getContainerIdFile()
    {
        return $this->containerIdFile;
    }

    public function setContainerIdFile($containerIdFile)
    {
        $this->containerIdFile = $containerIdFile;
    }

    public function getLxcConf()
    {
        return $this->lxcConf;
    }

    public function setLxcConf($lxcConf)
    {
        $this->lxcConf = $lxcConf;
    }

    public function getPrivileged()
    {
        return $this->privileged;
    }

    public function setPrivileged($privileged)
    {
        $this->privileged = $privileged;
    }

    public function getCapAdd()
    {
        return $this->capAdd;
    }

    public function setCapAdd($capAdd)
    {
        $this->capAdd = $capAdd;
    }

    public function getCapDrop()
    {
        return $this->capDrop;
    }

    public function setCapDrop($capDrop)
    {
        $this->capDrop = $capDrop;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function addLink($name, $alias)
    {
        $this->links[] = "$name:$alias";
    }

    public function getPortBindings()
    {
        return $this->portBindings;
    }

    public function addPortBinding(Port $port)
    {
        $this->portBindings[] = $port;
    }

    public function getPublishAll()
    {
        return $this->publishAllPorts;
    }

    public function setPublishAll($publishAll)
    {
        $this->publishAllPorts = $publishAll;
    }

    /****************************************************************/

    public function toArray()
    {
        return $data = (new HostConfigHydrator())->extract($this);
    }

    /**
     * Returns a host config object. There's no internal implementation yet
     * for the deserialization and thus an empty host config will be returned.
     *
     * @param array $data
     *
     * @return HostConfig
     */
    public function fromArray(array $data)
    {
        $config = new HostConfig();
        return (new HostConfigHydrator())->hydrate($data, $config);
    }

    /**
     * Returns this entity as a JSON.
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES);
    }

    /**
     * Returns a host config object. There's no internal implementation yet
     * for the deserialization and thus an empty container will be returned.
     *
     * @param string $json
     *
     * @return Config
     */
    public function fromJson($json)
    {
        // convert the json to an associative array
        $data = json_decode($json, true);
        return $this->fromArray($data);
    }
}
