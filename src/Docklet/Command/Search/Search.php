<?php
/**
 * Docklet (http://slopjong.github.io/docklet)
 *
 * @link      http://github.com/slopjong/docklet for the canonical source repository
 * @copyright Copyright (c) 2014 Romain Schmitz (http://slopjong.de)
 * @license   http://slopjong.github.io/docklet/license/new-bsd New BSD License
 */

namespace Docklet\Command\Search;


use Docklet\Command\AbstractCommand;
use Zend\Http\Response;

/**
 * Class Search
 *
 * @package Docklet\Command
 * @link https://docs.docker.com/reference/api/docker_remote_api_v1.14/#search-images
 */
class Search extends AbstractCommand
{
    protected $term;
    protected $automated;
    protected $noTrunc;
    protected $stars;

    /**
     * @param      $term
     * @param bool $automated (unused)
     * @param bool $noTrunc (unused)
     * @param int  $stars Get images with at least a specific amount of stars
     */
    public function __construct($term, $automated = false, $noTrunc = false, $stars = 0)
    {
        $this->term = $term;
        $this->automated = $automated;
        $this->noTrunc = $noTrunc;
        $this->stars = $stars;

        $this->command = 'images/search';
        $this->getUri()->setQuery(array('term' => $term));
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->getUri()->setPath($this->command);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function postExecute(Response $response, $returnContainer = false)
    {
        $content = parent::postExecute($response);

        // do nothing if we get plain text which Docker returns for
        // some complex commands.
        if ($data = json_decode($content)) {
            if ($this->stars) {
                $data = static::filterByStars($data);
            }
            $content = json_encode($data);
        } else {
            // @todo: throw a json decoding exception
        }

        return $content;
    }

    protected function filterByStars($searchResults)
    {
        $newResults = array();
        foreach ($searchResults as $result) {
            if ($result->star_count > $this->stars) {
                $newResults[] = $result;
            }
        }
        return $newResults;
    }
} 