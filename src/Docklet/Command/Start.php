<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command;

use Docklet\Container\Container;
use Zend\Http\Request;
use Zend\Http\Response;


/**
 * Class Start
 *
 * @package Docklet\Command
 * @link https://docs.docker.com/reference/api/docker_remote_api_v1.14/#start-a-container
 */
class Start extends AbstractCommand
{
    /** @var Container */
    protected $container = null;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->setMethod(Request::METHOD_POST);
        $this->setCommand('containers/' . $container->getId() . '/start');
//        $this->setContent($container->getHostConfig()->toJson());
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
            $this->container->setId($obj->Id);
            return $this->container->toJson();
        }

        // @todo: throw an exception, we should have got the ID here
    }
} 