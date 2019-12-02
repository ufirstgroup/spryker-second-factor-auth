<?php

namespace SprykerUFirst\Zed\SecondFactorAuth\Persistence;

use DateTime;
use Generated\Shared\Transfer\SpyUfgSecondFactorAuthSecretEntityTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * Class SecondFactorAuthEntityManager
 *
 * @package SprykerUFirst\Zed\SecondFactorAuth\Persistence
 *
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthPersistenceFactory getFactory()
 */
class SecondFactorAuthEntityManager extends AbstractEntityManager implements SecondFactorAuthEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function saveSecret(UserTransfer $userTransfer): void
    {
        $secretEntityTransfer = (new SpyUfgSecondFactorAuthSecretEntityTransfer())
            ->setFkUser($userTransfer->getIdUser())
            ->setSecret($userTransfer->getSecondFactorAuthSecret());
        $this->save($secretEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function deleteSecret(UserTransfer $userTransfer): void
    {
        $this->getFactory()
            ->createSpyUfgSecondFactorAuthSecretQuery()
            ->filterByFkUser($userTransfer->getIdUser())
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function removeAllTrustedDevicesForUser(UserTransfer $userTransfer): void
    {
        $oldTrustedDevicesQuery = $this->getFactory()
            ->createSpyUfgSecondFactorAuthTrustedDeviceQuery()
            ->filterByFkUser($userTransfer->getIdUser());
        $oldTrustedDevicesQuery->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function collectTrustedDeviceGarbage(UserTransfer $userTransfer): void
    {
        $oldTrustedDevices = $this->getFactory()
            ->createSpyUfgSecondFactorAuthTrustedDeviceQuery()
            ->filterByFkUser($userTransfer->getIdUser())
            ->filterByCreatedAt((new DateTime())->modify('-2 months')->format('Y-m-d'), Criteria::LESS_THAN);
        $oldTrustedDevices->delete();
    }
}
