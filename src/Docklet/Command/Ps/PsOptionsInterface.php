<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Ps;


/**
 * Interface PsOptionsInterface
 *
 * @package Docklet\Command\Ps
 *
 * @method PsOptionsInterface all(boolean $all) Show all containers. Only running containers are shown by default.
 * @method PsOptionsInterface limit(int $limit) Show limit last created containers, include non-running ones.
 * @method PsOptionsInterface since(string $id) Show only containers created since Id, include non-running ones.
 * @method PsOptionsInterface before(string $id) Show only containers created before Id, include non-running ones.
 * @method PsOptionsInterface size(boolean $showSize) Show the containers sizes.
 */
interface PsOptionsInterface
{
}
