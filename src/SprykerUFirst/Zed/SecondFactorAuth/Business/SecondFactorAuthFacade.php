<?php

/**
 * MIT License
 * See LICENSE file.
 */

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
     * {@inheritDoc}
     *
     * @api
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param string|null $bundle
     * @param string|null $controller
     * @param string|null $action
     *
     * @return bool
     */
    public function isIgnorablePath(?string $bundle, ?string $controller, ?string $action): bool
    {
        return $this->getFactory()
            ->createAuth()
            ->isIgnorablePath($bundle, $controller, $action);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $code
     *
     * @return bool
     */
    public function authenticate(string $code): bool
    {
        return $this->getFactory()->createAuth()->authenticate($code);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int|null $idUser
     *
     * @return void
     */
    public function unregisterUser(?int $idUser = null): void
    {
        $this->getFactory()->createAuth()->unregisterUser($idUser);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer|null $currentUserTransfer
     *
     * @return bool
     */
    public function isUserRegistered(?UserTransfer $currentUserTransfer = null): bool
    {
        return $this->getFactory()->createAuth()->isUserRegistered($currentUserTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function createSecret(): string
    {
        return $this->getFactory()->createAuth()->createSecret();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $secret
     *
     * @return string
     */
    public function getQrCodeUrl(string $secret): string
    {
        return $this->getFactory()->createAuth()->getUrl($secret);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer|null $currentUserTransfer
     *
     * @return bool
     */
    public function isIgnorableUser(?UserTransfer $currentUserTransfer = null): bool
    {
        return $this->getFactory()->createAuth()->isIgnorableUser($currentUserTransfer);
    }
}
