<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace DockletTest\Command;


// the auto-loader is not configured for unit test cases, including manually here
include_once('DockletTest/DockerTestCase.php');

use Docklet\Command\CommandInterface;
use Docklet\Command\Run\Run;
use DockletTest\DockerTestCase;
use Zend\Http\Request;

class RunTest extends DockerTestCase
{
    protected static $image = 'slopjong/apache';
    protected static $container_command = array(
        '/usr/sbin/apache2ctl',
        '-D',
        'FOREGROUND',
    );

    public function testRequestWithOnePortBinding()
    {
        $options = Run::options(static::$image, static::$container_command)
            ->portBinding('80', '8000')
            ->daemon(true);

        /** @var CommandInterface $command */
        $command = new Run($options);
        $request = $command->execute();
        $json = $request->getContent();
        $stdObj = json_decode($json);

        $this->assertNotNull($stdObj);
        $this->assertContains('"ExposedPorts":{"80/tcp":[]}', $json);

        return $command;
    }

    /**
     * @depends testRequestWithOnePortBinding
     */
    public function testExecuteRequestWithOnePortBinding(CommandInterface $command)
    {
        $request = static::$docker->buildRequest($command);
        $response = static::$docker->dispatch($request);
        $container = $command->postExecute($response, true);

        $information = static::dockerPs();

        $shortenedId = substr($container->getId(), 0, 12);

        $found = preg_match(
            '/'. $shortenedId .'.*0.0.0.0:8000->80\/tcp/m',
            $information
        );

        $this->assertTrue(filter_var($found, FILTER_VALIDATE_BOOLEAN));

        static::$removeContainersWithId[] = $container->getId();
    }

    public function testRequestWithTwoPortBindings()
    {
        $options = Run::options(static::$image, static::$container_command)
            ->portBinding('81', '8001')
            ->portBinding('82', '8002')
            ->daemon(true);

        /** @var CommandInterface $command */
        $command = new Run($options);
        $request = $command->execute();
        $json = $request->getContent();
        $stdObj = json_decode($json);

        $this->assertNotNull($stdObj);
        $this->assertContains('"ExposedPorts":{"81/tcp":[],"82/tcp":[]}', $json);

        return $command;
    }

    /**
     * @depends testRequestWithTwoPortBindings
     */
    public function testExecuteRequestWithTwoPortBindings(CommandInterface $command)
    {
        $request = static::$docker->buildRequest($command);
        $response = static::$docker->dispatch($request);
        $container = $command->postExecute($response, true);

        $information = static::dockerPs();

        $shortenedId = substr($container->getId(), 0, 12);

        $found = preg_match(
            '/'. $shortenedId .'.*0.0.0.0:8001->81\/tcp, 0.0.0.0:8002->82\/tcp/m',
            $information
        );

        $this->assertTrue(filter_var($found, FILTER_VALIDATE_BOOLEAN));

        static::$removeContainersWithId[] = $container->getId();
    }

    public function testRequestWithManyPortBindings()
    {
        $options = Run::options(static::$image, static::$container_command)
            ->portBinding('83', '8003')
            ->portBinding('84', '8004')
            ->portBinding('85', '8005')
            ->portBinding('86', '8006')
            ->portBinding('87', '8007')
            ->portBinding('88', '8008')
            ->daemon(true);

        /** @var CommandInterface $command */
        $command = new Run($options);
        $request = $command->execute();
        $json = $request->getContent();
        $stdObj = json_decode($json);

        $this->assertNotNull($stdObj);
        $this->assertContains(
            '"ExposedPorts":{"83/tcp":[],"84/tcp":[],"85/tcp":[],"86/tcp":[],"87/tcp":[],"88/tcp":[]}',
            $json
        );

        return $command;
    }

    /**
     * @depends testRequestWithManyPortBindings
     */
    public function testExecuteRequestWithManyPortBindings(CommandInterface $command)
    {
        $request = static::$docker->buildRequest($command);
        $response = static::$docker->dispatch($request);
        $container = $command->postExecute($response, true);

        $information = static::dockerPs();

        $shortenedId = substr($container->getId(), 0, 12);

        $found = preg_match(
            '/'. $shortenedId .'.*0.0.0.0:8003->83\/tcp, 0.0.0.0:8004->84\/tcp, 0.0.0.0:8005->85\/tcp, 0.0.0.0:8006->86\/tcp, 0.0.0.0:8007->87\/tcp, 0.0.0.0:8008->88\/tcp/m',
            $information
        );

        $this->assertTrue(filter_var($found, FILTER_VALIDATE_BOOLEAN));

        static::$removeContainersWithId[] = $container->getId();
    }

    public function testAllowedJsonKeys()
    {
        $options = Run::options(static::$image, static::$container_command)
            ->portBinding('80', '8000');

        /** @var CommandInterface $command */
        $command = new Run($options);
        $request = $command->execute();
        $json = $request->getContent();

        // they don't need to be in the order as defined in the hydrator
        $shouldBePresent = array(
            'Hostname', 'Domainname', 'User', 'Memory', 'MemorySwap', 'CpuShares',
            'Cpuset', 'AttachStdin', 'AttachStdout', 'AttachStderr', 'PortSpecs',
            'ExposedPorts', 'Tty', 'OpenStdin', 'StdinOnce', 'Env', 'Cmd', 'Image',
            'Volumes', 'WorkingDir', 'Entrypoint', 'OnBuild', 'NetworkDisabled'
        );

        $arePresent = array_keys(json_decode($json, true));

        $diff = array_merge(
            array_diff($shouldBePresent, $arePresent), // entries which are in $should but not in $are
            array_diff($arePresent, $shouldBePresent)  // entries which are in $are but not in $should
        );

        $this->assertEmpty($diff,
            "JSON key missing or key found which hasn't been reviewed yet: " . join(', ', $diff));
    }
}
