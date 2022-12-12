<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerUFirst\Zed\SecondFactorAuth\Persistence;

use Generated\Shared\Transfer\UserTransfer;

interface SecondFactorAuthEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function saveSecret(UserTransfer $userTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function deleteSecret(UserTransfer $userTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function removeAllTrustedDevicesForUser(UserTransfer $userTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function collectTrustedDeviceGarbage(UserTransfer $userTransfer): void;
}
