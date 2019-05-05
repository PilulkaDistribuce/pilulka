<?php
declare(strict_types=1);

namespace Atar\Web;

use Webmozart\Assert\Assert;

class RouteCollection
{

    private const KEY_METHOD = 0;
    private const KEY_PATTERN = 1;
    private const KEY_HANDLER = 2;

    /**
     * @var array
     */
    private $routes;


    /**
     * RouteCollection constructor.
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getPatternByKey(string $key)
    {
        Assert::keyExists($this->routes, $key);
        return $this->routes[$key][self::KEY_PATTERN];
    }

}