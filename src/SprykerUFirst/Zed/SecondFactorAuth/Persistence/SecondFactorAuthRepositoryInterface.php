<?php

namespace SprykerUFirst\Zed\SecondFactorAuth\Persistence;

use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\SecondFactorAuth\Persistence\SpyUfgSecondFactorAuthTrustedDeviceQuery;

/**
 * Class SecondFactorAuthQueryContainer
 *
 * @package SprykerUFirst\Zed\SecondFactorAuth\Persistence
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthPersistenceFactory getFactory()
 */
interface SecondFactorAuthRepositoryInterface
{
    /**
     * @param int $idUser
     * @param string $device
     *
     * @return \Orm\Zed\SecondFactorAuth\Persistence\SpyUfgSecondFactorAuthTrustedDeviceQuery
     */
    public function getTrustedDeviceByUserIdAndDeviceId(int $idUser, string $device): SpyUfgSecondFactorAuthTrustedDeviceQuery;

    /**
     * @param int $idUser
     *
     * @return \Orm\Zed\SecondFactorAuth\Persistence\SpyUfgSecondFactorAuthTrustedDeviceQuery
     */
    public function getTrustedDevicesByUserId(int $idUser): SpyUfgSecondFactorAuthTrustedDeviceQuery;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function addSecretToUserTransfer(UserTransfer $userTransfer): UserTransfer;

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function doesUserHaveSecret(int $idUser): bool;
}
