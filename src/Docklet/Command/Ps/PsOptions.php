<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Ps;


use Docklet\Container\Port;

/**
 * <strong>The</strong> ps options.
 *
 * @package Docklet\Command
 *
 * @method PsOptionsInterface all(boolean $all) Show all containers. Only running containers are shown by default.
 * @method PsOptionsInterface limit(int $limit) Show limit last created containers, include non-running ones.
 * @method PsOptionsInterface since(string $id) Show only containers created since Id or Name, include non-running ones.
 * @method PsOptionsInterface before(string $id) Show only container created before Id or Name, include non-running ones.
 * @method PsOptionsInterface size(boolean $showSize) Show the containers sizes.
 */
class PsOptions implements PsOptionsInterface
{
    public $all = false;
    public $limit = -1;
    public $since = '';
    public $before = '';
    public $size = false;

    public function __call($method, $params)
    {
        if (! property_exists($this, $method)) {
            throw new \Exception("Method $method() does not exist");
        }

        $this->$method = $params[0];
        return $this;
    }
}
