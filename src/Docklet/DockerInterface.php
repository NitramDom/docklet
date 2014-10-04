<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet;


use Docklet\Command\Ps\PsOptionsInterface;
use Docklet\Command\Run\RunOptionsInterface;

interface DockerInterface
{
    public function images();
    public function ps(PsOptionsInterface $options);
    public function run(RunOptionsInterface $options);
    public function restart($id, $wait = 10);
    public function stop($id, $wait = 10);
    public function pause($id);
    public function unpause($id);
    public function version();
} 