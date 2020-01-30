<?php

namespace SprykerUFirst\Zed\SecondFactorAuth\Business;

use Generated\Shared\Transfer\UserTransfer;

interface SecondFactorAuthFacadeInterface
{
    /**
     * @param string|null $device
     *
     * @return bool
     */
    public function isAuthenticated(?string $device): bool;

    /**
     * @param string|null $bundle
     * @param string|null $controller
     * @param string|null $action
     *
     * @return bool
     */
    public function isIgnorable(?string $bundle, ?string $controller, ?string $action): bool;

    /**
     * @param string $device
     *
     * @return void
     */
    public function trustDevice(string $device): void;

    /**
     * @param string $code
     *
     * @return bool
     */
    public function authenticate(string $code): bool;

    /**
     * @param string $secret
     * @param string $code
     *
     * @return bool
     */
    public function registerCurrentUser(string $secret, string $code): bool;

    /**
     * @param int|null $idUser
     *
     * @return void
     */
    public function unregisterUser(?int $idUser = null): void;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer|null $currentUserTransfer
     *
     * @return bool
     */
    public function isUserRegistered(?UserTransfer $currentUserTransfer = null): bool;

    /**
     * @return string
     */
    public function createSecret(): string;

    /**
     * @param string $secret
     *
     * @return string
     */
    public function getQrCodeUrl(string $secret): string;
}
