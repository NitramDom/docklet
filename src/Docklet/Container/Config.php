<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Container;


class Config
{
    protected $commands = array();
    protected $exposedPorts = array();
    protected $host = '';
    protected $image = '';
    protected $ttyMode = false;
    protected $volumes = array();

    public function __construct($image, $command = '')
    {
        if (! $image) {
            trigger_error('No image provided.');
        }

        $this->setImage($image);

        if ($command) {
            $this->addCommand($command);
        }
    }

    /**
     * Get a list of specified commands.
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Adds a new command to the command list.
     *
     * @param string $command
     * @return $this
     */
    public function addCommand($command)
    {
        $this->commands[] = $command;
        return $this;
    }

    /**
     * @return array
     */
    public function getExposedPorts()
    {
        return $this->exposedPorts;
    }

    /**
     * @param string $port
     * @return $this
     */
    public function addExposedPort($port)
    {
        $this->exposedPorts[] = $port;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;
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
    public function getTtyMode()
    {
        return $this->ttyMode;
    }

    /**
     * @param boolean $ttyMode
     * @return $this
     */
    public function setTtyMode($ttyMode)
    {
        $this->ttyMode = $ttyMode;
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
    public function addVolume($volume)
    {
        $this->volumes[] = $volume;
        return $this;
    }

    /****************************************************************/

    public function toArray()
    {
        return $data = (new ConfigHydrator())->extract($this);
    }

    /**
     * Returns a config object. There's no internal implementation yet
     * for the deserialization and thus an empty container will be returned.
     *
     * @param array $data
     *
     * @return Config
     */
    public function fromArray(array $data)
    {
        // suppress the user notice, we will set the image
        // during the hydration
        $config = @new Config('');

        return (new ConfigHydrator())->hydrate($data, $config);
    }

    /**
     * Returns this entity as a JSON.
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Returns a config object. There's no internal implementation yet
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