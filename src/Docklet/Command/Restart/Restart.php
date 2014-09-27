<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Restart;


use Docklet\Command\AbstractCommand;
use Docklet\Container\Container;
use Zend\Http\Request;
use Zend\Http\Response;


/**
 * Class Restart
 *
 * @package Docklet\Command
 * @link https://docs.docker.com/reference/api/docker_remote_api_v1.14/#restart-a-container
 */
class Restart extends AbstractCommand
{
    /** @var RestartOptions */
    protected $options = null;

    public function __construct($options)
    {
        switch (true) {
            case $options instanceof Container:

                /** @var Container $options */
                $container = $options;
                $this->options = new RestartOptions();
                $this->options->containerId($container->getId());
                $this->options->wait(10);
                break;

            case $options instanceof RestartOptions:

                $this->options = $options;
                break;

            default:
                throw new \InvalidArgumentException('Passed arguments must be a RestartOptions or a Container');
        }

        $this->setMethod(Request::METHOD_POST);
        $this->setCommand('containers/' . $this->options->containerId . '/restart');
        $this->getUri()->setQuery(array('t' => $this->options->wait));
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->getUri()->setPath($this->command);
        return $this;
    }
} 