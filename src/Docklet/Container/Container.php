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
    protected $command = '';
    protected $host = '';
    protected $id = '';
    protected $image = '';
    protected $interactive = false;
    protected $name = '';
    protected $sigProxy = true;
    protected $ttyMode = false;

    /**
     * @param string  $image
     * @param string  $command
     * @param boolean $ttyMode
     * @param boolean $interactive
     * @param boolean $sigProxy
     */
    public function __construct($image, $command = '', $ttyMode = false, $interactive = false, $sigProxy = true)
    {
        if (! $image) {
            trigger_error('No image provided.');
        }

        $this->image = $image;
        $this->command = $command;
        $this->ttyMode = $ttyMode;
        $this->interactive = $interactive;
        $this->sigProxy = $sigProxy;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $command
     * @return $this
     */
    public function setCommand($command)
    {
        $this->command = $command;
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

    /****************************************************************/

    public function toJson()
    {
        $data = (new Hydrator())->extract($this);
        return json_encode($data);
    }

    public function fromJson($json)
    {
        // convert the json to an associative array
        $data = json_decode($json, true);

        // suppress the user notice, we will set the image
        // during the hydration
        $container = @new Container('');
        
        return (new Hydrator())->hydrate($data, $container);
    }
}
