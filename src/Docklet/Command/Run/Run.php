<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Run;


use Docklet\Command\AbstractCommand;
use Docklet\Command\Attach\Attach;
use Docklet\Command\Create\Create;
use Docklet\Command\Start\Start;
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

    /** @var RunOptions */
    protected $options = null;

    /**
     * @param RunOptions $options
     */
    public function __construct(RunOptions $options)
    {
        $this->options = $options;

        $container = new Container();

        $config = new Config($options->image, $options->command);
        $hostConfig = new HostConfig();

        $config->setEnvironmentVars($options->environmentVariables);

        foreach ($options->portBindings as $port) {
            $hostConfig->addPortBinding($port);
            $config->addExposedPort($port);
        }

        if (!$options->daemon) {
            $config->setAttachStdout(true);
            $config->setAttachStderr(true);
        }

        $container->setConfig($config);
        $container->setHostConfig($hostConfig);

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

        $response = $this->start();

        if ($response->getStatusCode() === Response::STATUS_CODE_204) {
            if (! $this->options->daemon) {
                $response = $this->attach();
                $this->container->addLastCommandResult($response->getContent());
            }
        } else {
            // @todo throw an exception
        }

        if ($returnContainer || $this->options->returnContainer) {
            return $this->container;
        } else {
            return $this->container->toJson();
        }
    }

    //****************************************************************
    // Assemble a run configuration

    /**
     * @param string $image
     * @param string|array $command
     *
     * @return RunOptionsInterface
     */
    public static function options($image, $command)
    {
        $options = new RunOptions();
        $options->image($image);
        $options->command($command);
        return $options;
    }

    //****************************************************************
    // Helpers

    /**
     * Start the container.
     *
     * @return Response
     */
    protected function start()
    {
        /** @var Docker $docker */
        $docker = Docker::getInstance();

        // @todo: what about the attach? What does the shell client do?
        //        do we need it at all? we shouldn't create an exact
        //        clone of the shell client

        $start = new Start($this->container);
        $request = $docker->buildRequest($start);

        /** @var Response $response */
        $response = $docker->dispatch($request);

        return $response;
    }

    /**
     * Attach to the container and get the stdout output.
     *
     * @return Response
     */
    protected function attach()
    {
        /** @var Docker $docker */
        $docker = Docker::getInstance();

        $attach = new Attach($this->container);
        $request = $docker->buildRequest($attach);

        /** @var Response $response */
        $response = $docker->dispatch($request);

        return $response;
    }
}
