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
use Zend\Http\Headers;
use Zend\Http\Request;
use Zend\Http\Response;

/**
 * Class Create
 *
 * @package Docklet\Command
 * @link https://docs.docker.com/reference/api/docker_remote_api_v1.14/#create-a-container
 */
class Create extends AbstractCommand
{
    /**
     * @var Container
     */
    protected $container = null;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->setMethod(Request::METHOD_POST);
        $this->setHeaders((new Headers())->fromString('Content-Type: application/json'));
        $this->setCommand('containers/create');
        $this->setContent($container->getConfig()->toJson());
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
        }

        // @todo: throw an exception, we should have got the ID here

        if ($returnContainer) {
            return $this->container;
        } else {
            return $this->container->toJson();
        }
    }
}
