<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Start;


use Docklet\Command\AbstractCommand;
use Docklet\Container\Container;
use Zend\Http\Headers;
use Zend\Http\Request;
use Zend\Http\Response;


/**
 * This starts one container. Multiple container start is not supported.
 *
 * @package Docklet\Command
 * @link https://docs.docker.com/reference/api/docker_remote_api_v1.14/#start-a-container
 */
class Start extends AbstractCommand
{
    /** @var Container */
    protected $container = null;

    /**
     * @param StartOptions|Container $options
     * @throws \InvalidArgumentException
     */
    public function __construct($options)
    {
        switch (true) {
            case $options instanceof Container:

                $this->container = $options;
                break;

            case $options instanceof StartOptions:

                /** @var StartOptions $options */
                $options = $options;
                $this->container = new Container();
                $this->container
                    ->setId($options->containerId)
                    ->setHostConfig($options->hostConfig)
                    ->setName($options->name);
                break;

            default:
                throw new \InvalidArgumentException('Passed arguments must be a StartOptionsInterface or a Container');
        }

        $this->setMethod(Request::METHOD_POST);
        $this->setCommand('containers/' . $this->container->getId() . '/start');
        $this->setHeaders((new Headers())->fromString('Content-Type: application/json'));
        $this->setContent($this->container->getHostConfig()->toJson());
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->getUri()->setPath($this->command);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function postExecute(Response $response, $returnContainer = false)
    {
        if ($obj = json_decode(parent::postExecute($response))) {
            return $this->container->toJson();
        } else {
            trigger_error('Got invalid JSON. Could not retrieve the ID.', E_USER_WARNING);
            return '';
        }
    }
} 