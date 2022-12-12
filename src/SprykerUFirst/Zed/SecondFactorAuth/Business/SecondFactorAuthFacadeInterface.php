<?php

/**
 * MIT License
 * See LICENSE file.
 */

namespace SprykerUFirst\Zed\SecondFactorAuth\Business;

use Generated\Shared\Transfer\UserTransfer;

interface SecondFactorAuthFacadeInterface
{
    /**
     * Specification:
     * Retrieves if user is already authenticated via 2FA
     * - Returns true if authenticated
     * - Returns false otherwise
     *
     * @api
     *
     * @param string|null $device
     *
     * @return bool
     */
    public function isAuthenticated(?string $device): bool;

    /**
     * Specification:
     * Retrieves if the provided path is ignorable (no 2FA needed)
     * - Returns true if authenticated
     * - Returns false otherwise
     *
     * @api
     *
     * @param string|null $bundle
     * @param string|null $controller
     * @param string|null $action
     *
     * @return bool
     */
    public function isIgnorablePath(?string $bundle, ?string $controller, ?string $action): bool;

    /**
     * Specification:
     * Sets the cookie to trust the device
     *
     * @api
     *
     * @param string $device
     *
     * @return void
     */
    public function trustDevice(string $device): void;

    /**
     * Specification:
     * Verifies the provided code against 2FA and updates the current user to reflect this new status.
     * - Returns true if authenticated
     * - Returns false otherwise
     *
     * @api
     *
     * @param string $code
     *
     * @return bool
     */
    public function authenticate(string $code): bool;

    /**
     * Specification:
     * Enrolls the current user to 2FA authentication with the provided secret.
     * - Returns true if registered
     * - Returns false otherwise
     *
     * @api
     *
     * @param string $secret
     * @param string $code
     *
     * @return bool
     */
    public function registerCurrentUser(string $secret, string $code): bool;

    /**
     * Specification:
     * Removes 2FA authentication for the provided user id.
     * - Returns true if unregistered
     * - Returns false otherwise
     *
     * @api
     *
     * @param int|null $idUser
     *
     * @return void
     */
    public function unregisterUser(?int $idUser = null): void;

    /**
     * Specification:
     * Checks if user is enrolled in 2FA
     * - Returns true if user has 2FA enabled
     * - Returns false otherwise
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer|null $currentUserTransfer
     *
     * @return bool
     */
    public function isUserRegistered(?UserTransfer $currentUserTransfer = null): bool;

    /**
     * Specification:
     * Generates a secret and returns it.
     *
     * @api
     *
     * @return string
     */
    public function createSecret(): string;

    /**
     * Specification:
     * Generates and returns a QR code url for the provided secret
     *
     * @api
     *
     * @param string $secret
     *
     * @return string
     */
    public function getQrCodeUrl(string $secret): string;

    /**
     * Specification:
     * Checks if the user can be ignored in 2FA enforcement checks (no 2FA needed).
     * - Returns true if user can be ignored
     * - Returns false otherwise
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer|null $currentUserTransfer
     *
     * @return bool
     */
    public function isIgnorableUser(?UserTransfer $currentUserTransfer = null): bool;
}
