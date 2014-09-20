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
use Docklet\Command\Images;
use Docklet\Command\Ps;
use Docklet\Command\Run;
use Docklet\Command\Version;
use Docklet\Container\Config;
use Docklet\Container\Container;
use Zend\Http\Client;
use Zend\Http\Response;

class Docker extends Client implements DockerInterface
{
    protected $host;
    protected $version;
    protected static $instance = null;

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

        // let the user know if there's already an instance, chances are
        // that he actually wants to use getInstance()
        if (static::$instance) {
            trigger_error('Another docker instance already exists. You maybe want to use getInstance()');
        }

        static::$instance = $this;
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

    //****************************************************************
    // Below are the command proxy functions that are considered implemented/stable.

    /**
     * Returns the last created docker client. If there's none yet a new
     * instance will be created.
     *
     * @param string $host
     *
     * @return null
     */
    public static function getInstance($host = '')
    {
        if (! static::$instance) {
            static::$instance = new Docker($host);
        }
        return static::$instance;
    }

    public function images()
    {
        return $this->exec(new Images());
    }

    public function ps()
    {
        return $this->exec(new Ps());
    }

    public function run(Config $config)
    {
        return $this->exec(new Run($config));
    }

    public function version()
    {
        return $this->exec(new Version());
    }
}