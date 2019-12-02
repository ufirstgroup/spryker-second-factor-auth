<?php

namespace SprykerUFirst\Zed\SecondFactorAuth\Persistence;

use Orm\Zed\SecondFactorAuth\Persistence\SpyUfgSecondFactorAuthSecretQuery;
use Orm\Zed\SecondFactorAuth\Persistence\SpyUfgSecondFactorAuthTrustedDeviceQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthRepository getQueryContainer()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthConfig getConfig()
 */
class SecondFactorAuthPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SecondFactorAuth\Persistence\SpyUfgSecondFactorAuthTrustedDeviceQuery
     */
    public function createSpyUfgSecondFactorAuthTrustedDeviceQuery(): SpyUfgSecondFactorAuthTrustedDeviceQuery
    {
        return SpyUfgSecondFactorAuthTrustedDeviceQuery::create();
    }

    /**
     * @return \Orm\Zed\SecondFactorAuth\Persistence\SpyUfgSecondFactorAuthSecretQuery
     */
    public function createSpyUfgSecondFactorAuthSecretQuery(): SpyUfgSecondFactorAuthSecretQuery
    {
        return SpyUfgSecondFactorAuthSecretQuery::create();
    }
}
