<?php

namespace SprykerUFirst\Zed\SecondFactorAuth;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class SecondFactorAuthDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_AUTH = 'auth facade';
    public const FACADE_USER = 'user facade';
    public const CLIENT_SESSION = 'session client';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container[self::FACADE_AUTH] = function (Container $container) {
            return $container->getLocator()->auth()->facade();
        };

        return parent::provideCommunicationLayerDependencies($container);
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container[self::FACADE_USER] = function (Container $container) {
            return $container->getLocator()->user()->facade();
        };

        $container[self::CLIENT_SESSION] = function (Container $container) {
            return $container->getLocator()->session()->client();
        };

        return parent::provideBusinessLayerDependencies($container);
    }
}
