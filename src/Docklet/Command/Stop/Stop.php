<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Stop;


use Docklet\Command\AbstractCommand;
use Docklet\Exception\NoSuchContainerException;
use Docklet\Exception\ServerErrorException;
use Zend\Http\Request;
use Zend\Http\Response;


/**
 * Stop a running container by sending SIGTERM and then SIGKILL after a grace period.
 *
 * @package Docklet\Command
 * @link https://docs.docker.com/reference/api/docker_remote_api_v1.14/#stop-a-container
 */
class Stop extends AbstractCommand
{
    /**
     * @param $id container ID or name
     * @param int $wait Number of seconds to wait for the container to stop before killing it. Default is 10 seconds.
     */
    public function __construct($id, $wait = 10)
    {
        $this->setMethod(Request::METHOD_POST);
        $this->setCommand('containers/' . $id . '/stop');
        $this->getUri()->setQuery(array('t' => $wait));
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