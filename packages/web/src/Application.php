<?php
declare(strict_types=1);

namespace Atar\Web;

use Atar\Web\Exception\Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Webmozart\Assert\Assert;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

class Application
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Application constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function run(): void
    {
        /** @var RequestInterface $request */
        $request = $this->container->get(RequestInterface::class);
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->container->get(Dispatcher::class);
        try {
            list($handler, $variableCollection) = $dispatcher->dispatch($request);
        } catch (Exception $exception) {
            throw $exception;
        }
        /** @var Action $action */
        $action = $this->container->get($handler);
        Assert::isInstanceOf($action, Action::class);
        $response = $action($request, $variableCollection);
        (new SapiEmitter())->emit($response);
    }
}
