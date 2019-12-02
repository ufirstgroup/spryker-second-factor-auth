<?php

namespace SprykerUFirst\Zed\SecondFactorAuth\Communication\Plugin\EventDispatcher;

use SprykerUFirst\Shared\SecondFactorAuth\SecondFactorAuthConstants;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @codeCoverageIgnore
 *
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Communication\SecondFactorAuthCommunicationFactory getFactory()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Business\SecondFactorAuthFacadeInterface getFacade()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthConfig getConfig()
 */
class SecondFactorAuthorizationEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds a listener for the `\Symfony\Component\HttpKernel\KernelEvents::REQUEST` event, which will redirect to the login page if user is not authorized.
     *
     * @api
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addListener(KernelEvents::REQUEST, function (RequestEvent $event) {
            return $this->onKernelRequest($event);
        });

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     *
     * @return \Symfony\Component\HttpKernel\Event\GetResponseEvent
     */
    protected function onKernelRequest(RequestEvent $event): RequestEvent
    {
        $config = $this->getConfig();
        $authFacade = $this->getFactory()->getAuthFacade();
        $secondFactorAuthFacade = $this->getFacade();

        $request = $event->getRequest();

        $module = $request->attributes->get('module');
        $controller = $request->attributes->get('controller');
        $action = $request->attributes->get('action');

        if ($authFacade->isIgnorable($module, $controller, $action)) {
            return $event;
        }

        if ($secondFactorAuthFacade->isIgnorable($module, $controller, $action)) {
            return $event;
        }

        if ($secondFactorAuthFacade->isAuthenticated($request->cookies->get(SecondFactorAuthConstants::SECOND_FACTOR_AUTH_DEVICE_COOKIE_NAME))
            || (!$config->getIsSecondFactorAuthRequired() && !$secondFactorAuthFacade->isUserRegistered())
        ) {
            return $event;
        }

        if ($config->getIsSecondFactorAuthRequired() && !$secondFactorAuthFacade->isUserRegistered()) {
            $event->setResponse(new RedirectResponse($config->getSecondFactorAuthRegistrationUrl()));
        }

        $event->setResponse(new RedirectResponse($config->getSecondFactorAuthUrl()));

        return $event;
    }
}