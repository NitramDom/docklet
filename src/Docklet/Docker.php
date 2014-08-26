<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet;


use Docklet\Command\CommandInterface;
use Docklet\Command\Version;
use Zend\Http\Client;
use Zend\Http\Response;

class Docker extends Client
{
    protected $host;
    protected $version;

    public function __construct($host = '')
    {
        if (getenv('DOCKER_HOST')) {
            $this->host = getenv('DOCKER_HOST');
        } else {
            $this->host = 'unix:///var/run/docker.sock';
        }

        if ($host) {
            $this->host = $host;
        }

        $this->setOptions(array(
            'maxredirects' => 0,
            'timeout'      => 30
        ));

        $json = $this->exec(new Version());
        $versions = json_decode($json);
        $this->version = 'v'. $versions->ApiVersion;
    }

    public function exec(CommandInterface $command)
    {
        $request = $command->execute();

        $uri = array(
            $this->host . ':',
            $this->version,
            $request->getUri()->toString()
        );

        $request->setUri(join('/', $uri));
        $response = $this->send($request);

        // @todo: Future feature, ACL support or simply other plugins doing stuff here
        // Because we're 'man-in-the-middle' here, we could do some
        // authentication stuff e.g. disallow certain post processing.
        // In other words, we could implement an ACL that needed to be
        // passed to postExecute(). The ACL would need to be defined on
        // a higher application level. As of now ACL is not yet supported.

        return $command->postExecute($response);
    }
} 