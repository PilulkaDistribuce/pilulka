<?php
declare(strict_types=1);

namespace Atar\Web;

use Atar\Web\Link\RawWebalizer;
use LogicException;

class LinkGenerator
{
    /**
     * @var RouteCollection
     */
    private $collection;
    /**
     * @var \FastRoute\RouteParser\Std
     */
    private $routeParser;
    /**
     * @var array
     */
    private $parseCache;
    /**
     * @var LinkWebalizer
     */
    private $webalizer;

    /**
     * LinkGenerator constructor.
     * @param RouteCollection $collection
     */
    public function __construct(
        RouteCollection $collection,
        LinkWebalizer $webalizer
    )
    {
        $this->collection = $collection;
        $this->webalizer = $webalizer;
        $this->routeParser= new \FastRoute\RouteParser\Std();
        $this->parseCache = [];
    }

    public function generate(string $key, array $params = [])
    {
        $routes = $this->parseRoutes($key);
        foreach ($routes as $route) {
            $url = '';
            $paramIdx = 0;

            foreach ($route as $part) {
                if (is_string($part)) {
                    $url .= $part;
                    continue;
                }
                if ($paramIdx === count($params)) {
                    throw new LogicException('Not enough parameters given');
                }
                $url .= $this->webalizer->webalize($params[$part[0]]);
            }
            if ($paramIdx === count($params)) {
                return $url;
            }
        }
        throw new LogicException('Too many parameters given');
    }

    /**
     * @param string $key
     * @return array
     */
    private function parseRoutes(string $key)
    {
        if(!isset($this->parseCache[$key])) {
            $this->parseCache[$key] = $this->routeParser->parse($this->collection->getPatternByKey($key));
        }
        return $this->parseCache[$key];
    }

}
