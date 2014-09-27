<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Run;


/**
 * Interface RunOptionsInterface
 *
 * @package Docklet\Command\Run
 *
 * @method RunOptionsInterface daemon(boolean $daemon)
 * @method RunOptionsInterface ttyMode(boolean $mode)
 * @method RunOptionsInterface interactive(boolean $interactive)
 * @method RunOptionsInterface image(string $image)
 * @method RunOptionsInterface command(mixed $command)
 * @method RunOptionsInterface volumes(array $volumes)
 * @method RunOptionsInterface returnContainer(boolean $flag)
 * @method RunOptionsInterface environmentVariable($name, $value)
 * @method RunOptionsInterface portBinding($containerPort, $hostPort, $hostIp = '', $protocol = 'tcp')
 */
interface RunOptionsInterface
{
}
