<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Container;


use Docklet\Container\Hydrator\ConfigHydrator;

class Config
{
    protected $attachStdOut = false;
    protected $attachStdErr = false;
    protected $commands = array();
    protected $environmentVars = array();
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

        if ($command && is_string($command)) {
            $this->addCommand($command);
        }

        if ($command && is_array($command)) {
            foreach ($command as $cmd) {
                $this->addCommand($cmd);
            }
        }
    }

    public function getAttachStdOut()
    {
        return $this->attachStdOut;
    }

    public function setAttachStdOut($flag)
    {
        $this->attachStdOut = $flag;
    }

    public function getAttachStdErr()
    {
        return $this->attachStdErr;
    }

    public function setAttachStdErr($flag)
    {
        $this->attachStdErr = $flag;
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
     * @param string|array $command Single command string or an array with the actual command and its arguments
     * @return $this
     */
    public function addCommand($command)
    {
        if (!is_string($command)) {
            trigger_error("Command not a string");
            return;
        }
        $this->commands[] = $command;
        return $this;
    }

    /**
     * @return array Environment variables to be set in the container.
     */
    public function getEnvironmentVars()
    {
        return $this->environmentVars;
    }

    /**
     * Set environment variables.
     * @param array $env
     * @return $this;
     */
    public function setEnvironmentVars(array $env)
    {
        $this->environmentVars = $env;
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
        $this->exposedPorts[$port] = new \stdClass();
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
