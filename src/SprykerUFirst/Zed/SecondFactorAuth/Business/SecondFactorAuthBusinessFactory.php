<?php

namespace SprykerUFirst\Zed\SecondFactorAuth\Business;

use SprykerUFirst\Zed\SecondFactorAuth\Business\Model\Auth;
use SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthDependencyProvider;
use Spryker\Zed\User\Business\UserFacadeInterface;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

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
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\User\Business\UserFacadeInterface
     */
    private function getUserFacade(): UserFacadeInterface
    {
        return $this->getProvidedDependency(SecondFactorAuthDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Sonata\GoogleAuthenticator\GoogleAuthenticator
     */
    private function createGoogleAuthenticator(): GoogleAuthenticator
    {
        return new GoogleAuthenticator();
    }
}
