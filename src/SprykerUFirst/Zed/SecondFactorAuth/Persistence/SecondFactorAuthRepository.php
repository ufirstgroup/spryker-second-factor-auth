<?php

namespace SprykerUFirst\Zed\SecondFactorAuth\Persistence;

use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\SecondFactorAuth\Persistence\SpyUfgSecondFactorAuthTrustedDeviceQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
/**
 * Class SecondFactorAuthQueryContainer
 *
 * @package SprykerUFirst\Zed\SecondFactorAuth\Persistence
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthPersistenceFactory getFactory()
 */
class SecondFactorAuthRepository extends AbstractRepository implements SecondFactorAuthRepositoryInterface
{
    /**
     * @param int $idUser
     * @param string $device
     *
     * @return \Orm\Zed\SecondFactorAuth\Persistence\SpyUfgSecondFactorAuthTrustedDeviceQuery
     */
    public function getTrustedDeviceByUserIdAndDeviceId(int $idUser, string $device): SpyUfgSecondFactorAuthTrustedDeviceQuery
    {
        return $this->getTrustedDevicesByUserId($idUser)
            ->filterByDevice($device);
    }

    /**
     * @param int $idUser
     *
     * @return \Orm\Zed\SecondFactorAuth\Persistence\SpyUfgSecondFactorAuthTrustedDeviceQuery
     */
    public function getTrustedDevicesByUserId(int $idUser): SpyUfgSecondFactorAuthTrustedDeviceQuery
    {
        return $this->getFactory()
            ->createSpyUfgSecondFactorAuthTrustedDeviceQuery()
            ->filterByFkUser($idUser);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function addSecretToUserTransfer(UserTransfer $userTransfer): UserTransfer
    {
        $query = $this->getFactory()
            ->createSpyUfgSecondFactorAuthSecretQuery()
            ->filterByFkUser($userTransfer->getIdUser());

        $secretEntityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        if ($secretEntityTransfer) {
            $userTransfer->setSecondFactorAuthSecret($secretEntityTransfer->getSecret());
        }

        return $userTransfer;
    }
}
