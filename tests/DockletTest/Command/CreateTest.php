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

use Docklet\Command\Create\Create;
use Docklet\Command\Create\CreateOptions;
use DockletTest\DockerTestCase;
use Zend\Stdlib\ResponseInterface;

class CreateTest extends DockerTestCase
{
    public function testCreateContainerFromExistingImage()
    {
        $options = new CreateOptions('slopjong/apache', 'ls');
        $command = new Create($options);

        $request = static::$docker->buildRequest($command);

        /** @var ResponseInterface $response */
        $response = static::$docker->dispatch($request);

        // test the returned container object
        $container = $command->postExecute($response, true);
        $this->assertNotEmpty($container->getId());

        // test the returned JSON
        $json = $command->postExecute($response);
        $stdObj = json_decode($json);
        $this->assertNotNull($stdObj);
        $this->assertNotEmpty($stdObj->Id);

        // remember the container ID to remove the container after the tests
        static::$removeContainersWithId[] = $container->getId();
    }

    public function testCreateContainerFromNonExistingImage()
    {
        $options = new CreateOptions('docklet/docklet', 'ls');
        $command = new Create($options);

        $request = static::$docker->buildRequest($command);

        /** @var ResponseInterface $response */
        $response = static::$docker->dispatch($request);

        // test the returned container object
        $container = $command->postExecute($response, true);
        $this->assertEmpty($container->getId());

        // test the returned JSON
        $json = $command->postExecute($response);
        $stdObj = json_decode($json);
        $this->assertNotNull($stdObj);
        $this->assertEmpty($stdObj->Id);
    }
}
