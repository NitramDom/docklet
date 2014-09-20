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

interface CommandInterface
{
    /**
     * Creates a request object with the API route including the query
     * and POST data if the command sets any.
     *
     * @return Request
     * @throws BadCommandException
     */
    public function execute();

    /**
     * Some post-processing of the returned Docker data. This can be useful
     * if a command provides arguments that are not used for the actual
     * Docker API but for local filtering or other tasks.
     *
     * @param Response  $response
     * @param boolean   $returnContainer
     * @return string
     */
    public function postExecute(Response $response, $returnContainer = false);
} 