<?php

namespace SprykerUFirst\Zed\SecondFactorAuth\Business;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * Class SecondFactorAuthFacade
 *
 * @package SprykerUFirst\Zed\SecondFactorAuth\Business
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Business\SecondFactorAuthBusinessFactory getFactory()
 */
class SecondFactorAuthFacade extends AbstractFacade implements SecondFactorAuthFacadeInterface
{
    /**
     * @param string|null $device
     *
     * @return bool
     */
    public function isAuthenticated(?string $device): bool
    {
        return $this->getFactory()
            ->createAuth()
            ->isAuthenticated($device);
    }

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isIgnorable(string $bundle, string $controller, string $action): bool
    {
        return $this->getFactory()
            ->createAuth()
            ->isIgnorablePath($bundle, $controller, $action);
    }

    /**
     * @param string $device
     *
     * @return void
     */
    public function trustDevice(string $device): void
    {
        $this->getFactory()
            ->createAuth()
            ->trustDevice($device);
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public function authenticate(string $code): bool
    {
        return $this->getFactory()->createAuth()->authenticate($code);
    }

    /**
     * @param string $secret
     * @param string $code
     *
     * @return bool
     */
    public function registerCurrentUser(string $secret, string $code): bool
    {
        return $this->getFactory()->createAuth()->registerCurrentUser($secret, $code);
    }

    /**
     * @param int|null $idUser
     *
     * @return void
     */
    public function unregisterUser(?int $idUser = null): void
    {
        $this->getFactory()->createAuth()->unregisterUser($idUser);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer|null $currentUserTransfer
     *
     * @return bool
     */
    public function isUserRegistered(?UserTransfer $currentUserTransfer = null): bool
    {
        return $this->getFactory()->createAuth()->isUserRegistered($currentUserTransfer);
    }

    /**
     * @return string
     */
    public function createSecret(): string
    {
        return $this->getFactory()->createAuth()->createSecret();
    }

    /**
     * @param string $secret
     *
     * @return string
     */
    public function getQrCodeUrl(string $secret): string
    {
        return $this->getFactory()->createAuth()->getUrl($secret);
    }
}
