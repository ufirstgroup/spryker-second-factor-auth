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
     * @uses \Spryker\Zed\SecurityGui\SecurityGuiConfig::HOME_PATH
     *
     * @var string
     */
    public const HOME_PATH = '/';

    /**
     * @var array
     */
    protected $ignorablePaths = [
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
    public function getIgnorablePaths(): array
    {
        return $this->ignorablePaths;
    }

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return void
     */
    public function addIgnorablePath(string $bundle, string $controller, string $action): void
    {
        $this->ignorablePaths[] = [
            'bundle' => $bundle,
            'controller' => $controller,
            'action' => $action,
        ];
    }

    /**
     * @return string
     */
    public function getHomePath(): string
    {
        return static::HOME_PATH;
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
