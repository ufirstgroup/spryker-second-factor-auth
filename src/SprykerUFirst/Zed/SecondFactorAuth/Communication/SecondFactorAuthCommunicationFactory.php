<?php

namespace SprykerUFirst\Zed\SecondFactorAuth\Communication;

use SprykerUFirst\Zed\SecondFactorAuth\Communication\Form\AuthenticationForm;
use SprykerUFirst\Zed\SecondFactorAuth\Communication\Form\RegistrationForm;
use SprykerUFirst\Zed\SecondFactorAuth\Communication\Form\UnregistrationForm;
use SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthDependencyProvider;
use Spryker\Zed\Auth\Business\AuthFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthConfig getConfig()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthRepository getQueryContainer()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Business\SecondFactorAuthFacadeInterface getFacade()
 */
class SecondFactorAuthCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAuthenticationForm(): FormInterface
    {
        return $this->getFormFactory()->create(AuthenticationForm::class);
    }

    /**
     * @param string $secret
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRegistrationForm(string $secret): FormInterface
    {
        return $this->getFormFactory()->create(RegistrationForm::class, null, [RegistrationForm::SECRET => $secret]);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createUnregistrationForm(): FormInterface
    {
        return $this->getFormFactory()->create(UnregistrationForm::class);
    }

    /**
     * @return \Spryker\Zed\Auth\Business\AuthFacadeInterface
     */
    public function getAuthFacade(): AuthFacadeInterface
    {
        return $this->getProvidedDependency(SecondFactorAuthDependencyProvider::FACADE_AUTH);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormFactory()
    {
        $container = $this->createContainerWithProvidedDependencies();

        return $container[self::FORM_FACTORY];
    }
}
