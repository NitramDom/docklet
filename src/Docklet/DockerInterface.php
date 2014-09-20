<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet;


use Docklet\Command\Options\RunOptions;

interface DockerInterface
{
    public function images();
    public function ps();
    public function run(RunOptions $options);
    public function stop($id, $wait = 10);
    public function version();
} 