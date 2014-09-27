<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Create;


use Docklet\Command\AbstractCommand;
use Docklet\Container\Container;
use Zend\Http\Headers;
use Zend\Http\Request;
use Zend\Http\Response;
use InvalidArgumentException;

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
     * @param CreateOptions|Container $options
     * @throws \InvalidArgumentException
     */
    public function __construct($options)
    {
        switch (true) {
            case $options instanceof Container:

                $this->container = $options;
                break;

            case $options instanceof CreateOptions:

                /** @var CreateOptions $options */
                $options = $options;
                $this->container = new Container();
                $this->container->setConfig($options->config);
                break;

            default:
                throw new \InvalidArgumentException('Passed arguments must be a CreateOptions or a Container');
        }

        $this->setMethod(Request::METHOD_POST);
        $this->setHeaders((new Headers())->fromString('Content-Type: application/json'));
        $this->setCommand('containers/create');
        $this->setContent($this->container->getConfig()->toJson());
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

    /**
     * @param string $image
     * @param string|array $command
     *
     * @return CreateOptions
     */
    public static function options($image, $command)
    {
        $options = new CreateOptions($image, $command);
        return $options;
    }
}
