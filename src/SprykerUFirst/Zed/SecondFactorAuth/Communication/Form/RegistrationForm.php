<?php

namespace SprykerUFirst\Zed\SecondFactorAuth\Communication\Form;

use SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthConfig getConfig()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Business\SecondFactorAuthFacadeInterface getFacade()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthRepositoryInterface getQueryContainer()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Communication\SecondFactorAuthCommunicationFactory getFactory()
 */
class RegistrationForm extends AbstractType
{
    public const FIELD_CODE = 'code';
    public const SECRET = 'secret';

    /**
     * @codeCoverageIgnore reason: no logic
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(self::SECRET);
        parent::configureOptions($resolver);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setAction(SecondFactorAuthConfig::URL_REGISTRATION_REGISTER);
        $this->addCodeField($builder);
        $this->addSecretField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCodeField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_CODE, TextType::class, [
                'constraints' => [
                    new Required(),
                    new NotBlank(),
                ],
                'attr' => [
                    'placeholder' => 'Authenticator Code',
                    'autofocus' => true,
                    'autocomplete' => 'off'
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSecretField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::SECRET, HiddenType::class, [
            'data' => $options[self::SECRET],
        ]);

        return $this;
    }
}
