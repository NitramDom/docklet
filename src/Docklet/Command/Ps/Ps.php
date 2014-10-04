<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Ps;
use Docklet\Command\AbstractCommand;
use Zend\Http\Headers;
use Zend\Http\Request;
use Zend\Http\Response;


/**
 * Class Ps
 *
 * @package Docklet\Command
 * @link https://docs.docker.com/reference/api/docker_remote_api_v1.14/#list-containers
 */
class Ps extends AbstractCommand
{
    /** @var PsOptions */
    protected $options = null;

    public function __construct(PsOptionsInterface $options = null)
    {
        $this->options = $options;
    }

    public function execute()
    {
        $this->setMethod(Request::METHOD_GET);
        $this->setHeaders((new Headers())->fromString('Content-Type: application/json'));
        $this->setCommand('containers/json');

        if ($this->options) {
            $this->getUri()->setQuery(array('all' => $this->options->all));
            $this->getUri()->setQuery(array('before' => $this->options->before));
            $this->getUri()->setQuery(array('since' => $this->options->since));
            $this->getUri()->setQuery(array('limit' => $this->options->limit));
            $this->getUri()->setQuery(array('size' => $this->options->size));
        }

        return $this;
    }

    public function postExecute(Response $response, $returnContainer = false)
    {
        return $response->getBody();
    }

    /**
     * @return PsOptionsInterface
     */
    public static function options()
    {
        $options = new PsOptions();
        return $options;
    }
} 