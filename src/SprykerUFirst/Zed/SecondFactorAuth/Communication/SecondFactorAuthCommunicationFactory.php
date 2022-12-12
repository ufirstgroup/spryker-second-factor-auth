<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerUFirst\Zed\SecondFactorAuth\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerUFirst\Zed\SecondFactorAuth\Communication\Form\AuthenticationForm;
use SprykerUFirst\Zed\SecondFactorAuth\Communication\Form\RegistrationForm;
use SprykerUFirst\Zed\SecondFactorAuth\Communication\Form\UnregistrationForm;
use Symfony\Component\Form\FormFactoryInterface;
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
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormFactory(): FormFactoryInterface
    {
        $container = $this->createContainerWithProvidedDependencies();

        return $container[static::FORM_FACTORY];
    }
}
