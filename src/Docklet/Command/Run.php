<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command;

use Docklet\Container\Config;
use Docklet\Container\Container;
use Docklet\Container\HostConfig;
use Docklet\Docker;
use Zend\Http\Response;

/**
 * Class Run
 *
 * @package Docklet\Command
 * @link https://docs.docker.com/reference/api/docker_remote_api_v1.14/#31-inside-docker-run
 */
class Run extends AbstractCommand
{
    /** @var \Docklet\Container\Container */
    protected $container = null;

    /** @var Create */
    protected $create = null;

//    /** @var Start */
//    protected $start = null;

    public function __construct(Config $config)
    {
        $container = new Container();
        $container->setConfig($config);
        $container->setHostConfig(new HostConfig());
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->create = new Create($this->container);
        return $this->create->execute();
    }

    /**
     * @inheritdoc
     */
    public function postExecute(Response $response, $returnContainer = false)
    {
        if ($response->getStatusCode() === Response::STATUS_CODE_404) {
            // @todo: the image wasn't found, try to pull it
            //        and throw an exception if that wasn't found too
        }

        /** @var Container $container */
        $this->container = $this->create->postExecute($response, true);

        // @todo: start the container
        // @todo: return the container ID if in detached mode

        $start = new Start($this->container);

        /** @var Docker $docker */
        $docker = Docker::getInstance();
        $request = $docker->buildRequest($start);

        /** @var Response $response */
        $response = $docker->dispatch($request);

        if ($returnContainer) {
            return $this->container;
        } else {
            return $this->container->toJson();
        }
    }
} 