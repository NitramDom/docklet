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
 * Class Attach
 *
 * @package Docklet\Command
 * @link https://docs.docker.com/reference/api/docker_remote_api_v1.14/#attach-to-a-container
 */
class Attach extends AbstractCommand
{
    /** @var Container */
    protected $container = null;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->setMethod(Request::METHOD_POST);
        $this->setHeaders((new Headers())->fromString('Content-Type: plain/text'));
        $this->setHeaders((new Headers())->fromString('Content-Length: 0'));
        $this->setCommand('containers/' . $container->getId() . '/attach');
        $this->getUri()->setQuery(array(
                'stderr' => 1,
                'stdout' => 1,
                'stream' => 1,
            ));
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