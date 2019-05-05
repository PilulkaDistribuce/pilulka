<?php
declare(strict_types=1);

namespace Atar\Web\Container;

use League\Container\Container;
use League\Container\ReflectionContainer;
use Psr\Container\ContainerInterface;

class Factory
{

    public function create(array $providers = []): ContainerInterface
    {
        $container = new Container();
        $container->share(ContainerInterface::class, $container);
        $container->delegate(new ReflectionContainer());
        foreach ($providers as $providerClass) {
            $container->addServiceProvider(
                $container->get($providerClass)
            );
        }
        return $container;
    }
}
