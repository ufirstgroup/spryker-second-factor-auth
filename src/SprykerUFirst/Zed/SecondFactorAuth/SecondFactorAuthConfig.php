<?php

namespace SprykerUFirst\Zed\SecondFactorAuth;

use Spryker\Shared\Application\ApplicationConstants;
use SprykerUFirst\Shared\SecondFactorAuth\SecondFactorAuthConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SecondFactorAuthConfig extends AbstractBundleConfig
{
    public const SECOND_FACTOR_URL = '/second-factor-auth/authentication';
    public const URL_REGISTRATION = '/second-factor-auth/registration';
    public const URL_REGISTRATION_REGISTER = '/second-factor-auth/registration/register';
    public const URL_REGISTRATION_UNREGISTER = '/second-factor-auth/registration/unregister';
    public const URL_USER_UNREGISTER = '/second-factor-auth/user/unregister';

    /**
     * @var array
     */
    protected $ignorable = [
        [
            'bundle' => 'security-gui',
            'controller' => '*',
            'action' => '*',
        ],
        [
            'bundle' => 'second-factor-auth',
            'controller' => 'authentication',
            'action' => 'index',
        ],
        [
            'bundle' => 'second-factor-auth',
            'controller' => 'registration',
            'action' => '*',
        ],
    ];

    /**
     * @return string
     */
    public function getSecondFactorAuthUrl(): string
    {
        return static::SECOND_FACTOR_URL;
    }

    /**
     * @return array
     */
    public function getIgnorable(): array
    {
        return $this->ignorable;
    }

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return void
     */
    public function addIgnorable(string $bundle, string $controller, string $action): void
    {
        $this->ignorable[] = [
            'bundle' => $bundle,
            'controller' => $controller,
            'action' => $action,
        ];
    }

    /**
     * @return string
     */
    public function getSecondFactorAuthRegistrationUrl(): string
    {
        return static::URL_REGISTRATION;
    }

    /**
     * @return string
     */
    public function getHostname(): string
    {
        return $this->get(ApplicationConstants::BASE_URL_ZED);
    }

    /**
     * @return bool
     */
    public function getIsSecondFactorAuthRequired(): bool
    {
        return $this->get(SecondFactorAuthConstants::SECOND_FACTOR_AUTH_REQUIRED, false);
    }
}
