<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command;


use Docklet\Command\Exception\BadCommandException;
use Zend\Http\Request;
use Zend\Http\Response;

class AbstractCommand extends Request implements CommandInterface
{
    /** @var string $command */
    protected $command;

    /** @var array $arguments */
    protected $arguments;

    public function __construct()
    {
        // turn the command class into the actual docker command
        $this->command = strtolower(
            array_pop(explode("\\", get_class($this)))
        );
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        throw new BadCommandException(sprintf(
            'Bad command call: %s',
            get_class($this)
        ));
    }

    /**
     * @inheritdoc
     */
    public function postExecute(Response $response, $returnContainer = false)
    {
        return $response->getBody();
    }

    protected function setCommand($commandString)
    {
        $this->command = $commandString;
        $this->getUri()->setPath($commandString);
    }
}