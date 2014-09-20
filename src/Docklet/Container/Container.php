<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Container;


class Container
{
    protected $id = '';
    protected $created = '';
    protected $path = '';
    protected $args = array();

    /** @var Config */
    protected $config = null;

    /** @var HostConfig */
    protected $hostConfig = null;

    /** @var State   */
    protected $state = null;

    /** @var array unofficial property that collects the stdout output from the start command */
    protected $lastCommandResults = array();

    protected $image = '';

    /** @var NetworkSettings */
    protected $networkSettings = null;

    protected $sysInitPath = '';
    protected $resolvConfPath = '';
    protected $volumes = array();

    /****************************************************************/

    protected $interactive = false;
    protected $name = '';
    protected $sigProxy = true;

    /**
     * @param string  $image
     * @param string  $command
     * @param boolean $ttyMode
     * @param boolean $interactive
     * @param boolean $sigProxy
     */
    public function __construct($image = '', $command = '', $ttyMode = false, $interactive = false, $sigProxy = true)
    {
//        if (! $image) {
//            trigger_error('No image provided.');
//        }

        $this->image = $image;
        $this->commands[] = $command;
        $this->ttyMode = $ttyMode;
        $this->interactive = $interactive;
        $this->sigProxy = $sigProxy;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param Config $config
     * @return $this
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return HostConfig
     */
    public function getHostConfig()
    {
        return $this->hostConfig;
    }

    /**
     * @param HostConfig $hostConfig
     * @return $this
     */
    public function setHostConfig(HostConfig $hostConfig)
    {
        $this->hostConfig = $hostConfig;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return bool
     */
    public function getInteractive()
    {
        return $this->interactive;
    }

    /**
     * @param boolean $interactive
     * @return $this
     */
    public function setInteractive($interactive)
    {
        $this->interactive = $interactive;
        return $this;
    }

    /**
     * @return array
     */
    public function getLastCommandResults()
    {
        return $this->lastCommandResults;
    }

    /**
     * @param $result
     * @return $this
     */
    public function addLastCommandResult($result)
    {
        $this->lastCommandResults[] = $result;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }



    /**
     * @return bool
     */
    public function getSigProxy()
    {
        return $this->sigProxy;
    }

    /**
     * @param boolean $sigProxy
     * @return $this
     */
    public function setSigProxy($sigProxy)
    {
        $this->sigProxy = $sigProxy;
        return $this;
    }

    /**
     * @return array
     */
    public function getVolumes()
    {
        return $this->volumes;
    }

    /**
     * Adds a new command to the command list.
     *
     * @param string $volume
     * @return $this
     */
    public function setVolume($volume)
    {
        $this->volumes[] = $volume;
        return $this;
    }

    /****************************************************************/

    /**
     * Returns this entity as a JSON.
     *
     * @return string
     */
    public function toJson()
    {
        $data = (new ContainerHydrator())->extract($this);
        return json_encode($data);
    }

    /**
     * Returns a container object. There's no internal implementation yet
     * for the deserialization and thus an empty container will be returned.
     *
     * @param $json
     *
     * @return Container
     */
    public function fromJson($json)
    {
        // convert the json to an associative array
        $data = json_decode($json, true);

        // suppress the user notice, we will set the image
        // during the hydration
        $container = @new Container('');
        
        return (new ContainerHydrator())->hydrate($data, $container);
    }
}
