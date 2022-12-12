<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerUFirst\Zed\SecondFactorAuth\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthConfig getConfig()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Business\SecondFactorAuthFacadeInterface getFacade()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthRepositoryInterface getQueryContainer()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Communication\SecondFactorAuthCommunicationFactory getFactory()
 */
class AuthenticationForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_CODE = 'code';

    /**
     * @var string
     */
    public const FIELD_TRUST_DEVICE = 'trust_device';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addCodeField($builder)
            ->addTrustedDeviceCheckbox($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCodeField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_CODE, TextType::class, [
                'constraints' => [
                    new Required(),
                    new NotBlank(),
                ],
                'attr' => [
                    'placeholder' => 'Authenticator Code',
                    'autofocus' => true,
                    'autocomplete' => 'off',
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTrustedDeviceCheckbox(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TRUST_DEVICE, CheckboxType::class, [
            'label' => 'Trust this device and don\'t ask for second factor for 2 weeks.',
            'required' => false,
        ]);

        return $this;
    }
}
