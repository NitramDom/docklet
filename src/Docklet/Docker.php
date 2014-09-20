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
use Docklet\Command\Options\RunOptions;
use Docklet\Command\Pause;
use Docklet\Command\Ps;
use Docklet\Command\Run;
use Docklet\Command\Stop;
use Docklet\Command\Unpause;
use Docklet\Command\Version;
use Zend\Http\Client;
use Zend\Http\Response;

class Docker extends Client implements DockerInterface
{
    protected $host;
    protected $version;

    /** @var DockerInterface */
    protected static $instance = null;

    /**
     * Creates a new Docker instance. It can be provided with a host
     * either by specifying the host parameter or by setting the environment
     * variable DOCKER_HOST on your system.
     *
     * Docker hosts look like:
     *
     * <ul>
     *   <li>unix:///var/run/docker.sock</li>
     *   <li>tcp://127.0.0.1:9999</li>
     * </ul>
     *
     * @param string $host
     */
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
            'timeout'      => 30,
            'useragent'    => 'Docklet',
        ));

        $json = $this->exec(new Version(), true);
        $versions = json_decode($json);
        $this->version = 'v'. $versions->ApiVersion;

        // let the user know if there's already an instance, chances are
        // that he actually wants to use getInstance()
        if (static::$instance) {
            trigger_error('Another docker instance already exists. You maybe want to use getInstance()');
        }

        static::$instance = $this;
    }

    /**
     * @param CommandInterface $command
     * @param bool             $noVersion the request should not contain the version part
     *
     * @return string
     */
    public function exec(CommandInterface $command, $noVersion = false)
    {
        $request = $this->buildRequest($command, $noVersion);
        $response = $this->send($request);

        // @todo: Future feature, ACL support or simply other plugins doing stuff here
        // Because we're 'man-in-the-middle' here, we could do some
        // authentication stuff e.g. disallow certain post processing.
        // In other words, we could implement an ACL that needed to be
        // passed to postExecute(). The ACL would need to be defined on
        // a higher application level. As of now ACL is not yet supported.

        return $command->postExecute($response);
    }

    /**
     * Assembles the request URI string from a given command.
     *
     * @param CommandInterface $command
     * @param boolean          $noVersion the request should not contain the version part
     *
     * @return \Zend\Http\Request
     */
    public function buildRequest(CommandInterface $command, $noVersion = false)
    {
        $request = $command->execute();

        $uri = array();

        if (preg_match('/^unix/', $this->host)) {
            $uri[] = $this->host . ':';
        } else {
            $uri[] = $this->host;
        }

        if (!$noVersion) {
            $uri[] = $this->version;
        }

        $uri[] = $request->getUri()->toString();

        return $request->setUri(join('/', $uri));
    }

    //****************************************************************
    // Below are the command proxy functions that are considered implemented/stable.

    /**
     * Returns the last created docker client. If there's none yet a new
     * instance will be created.
     *
     * @param string $host
     *
     * @return DockerInterface
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

    public function run(RunOptions $options)
    {
        return $this->exec(new Run($options));
    }

    public function stop($id, $wait = 10)
    {
        return $this->exec(new Stop($id, $wait));
    }

    public function pause($id)
    {
        return $this->exec(new Pause($id));
    }

    public function unpause($id)
    {
        return $this->exec(new Unpause($id));
    }

    public function version()
    {
        return $this->exec(new Version());
    }
}
