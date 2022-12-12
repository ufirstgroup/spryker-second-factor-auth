<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerUFirst\Zed\SecondFactorAuth\Business;

use PragmaRX\Google2FA\Google2FA;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\User\Business\UserFacadeInterface;
use SprykerUFirst\Zed\SecondFactorAuth\Business\Model\Auth;
use SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthDependencyProvider;

/**
 * Class SecondFactorAuthBusinessFactory
 *
 * @package SprykerUFirst\Zed\SecondFactorAuth\Business
 * @method \SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthConfig getConfig()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthRepositoryInterface getRepository()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthEntityManagerInterface getEntityManager()
 */
class SecondFactorAuthBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerUFirst\Zed\SecondFactorAuth\Business\Model\Auth
     */
    public function createAuth(): Auth
    {
        return new Auth(
            $this->getUserFacade(),
            $this->getConfig(),
            $this->getRepository(),
            $this->createGoogleAuthenticator(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected function getUserFacade(): UserFacadeInterface
    {
        return $this->getProvidedDependency(SecondFactorAuthDependencyProvider::FACADE_USER);
    }

    /**
     * @return \PragmaRX\Google2FA\Google2FA
     */
    protected function createGoogleAuthenticator(): Google2FA
    {
        return new Google2FA();
    }
}
