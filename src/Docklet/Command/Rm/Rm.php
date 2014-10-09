<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Rm;


use Docklet\Command\AbstractCommand;
use Docklet\Exception\NoSuchContainerException;
use Docklet\Exception\ServerErrorException;
use Zend\Http\Request;
use Zend\Http\Response;


/**
 * Remove a container.
 *
 * @package Docklet\Command
 * @link https://docs.docker.com/reference/api/docker_remote_api_v1.14/#remove-a-container
 */
class Rm extends AbstractCommand
{
    /**
     * Note: the force parameter doesn't work properly. You need to stop
     * the container first and remove it then by omitting $force.
     *
     * @param         $id    container ID or name
     * @param boolean $force Force the removal of a running container (uses SIGKILL).
     */
    public function __construct($id, $force = false)
    {
        $this->setMethod(Request::METHOD_DELETE);
        $this->setCommand('containers/' . $id);
        $this->getUri()->setQuery(array('f' => $force));
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->getUri()->setPath($this->command);
        return $this;
    }

    public function postExecute(Response $response, $returnContainer = false)
    {
        // @todo: document in the interface what exceptions can be thrown
        switch($response->getStatusCode()) {
            case Response::STATUS_CODE_404:
                throw new NoSuchContainerException();
                break;
            case Response::STATUS_CODE_500:
                throw new ServerErrorException();
                break;
        }
    }
} 