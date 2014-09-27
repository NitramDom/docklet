<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Unpause;


use Docklet\Command\AbstractCommand;
use Zend\Http\Request;
use Zend\Http\Response;


/**
 * Class Unpause
 *
 * @package Docklet\Command
 * @link https://docs.docker.com/reference/api/docker_remote_api_v1.14/#unpause-a-container
 */
class Unpause extends AbstractCommand
{
    public function __construct($id)
    {
        $this->setMethod(Request::METHOD_POST);
        $this->setCommand('containers/' . $id . '/unpause');
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