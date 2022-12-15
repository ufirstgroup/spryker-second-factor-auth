<?php

/**
 * MIT License
 * See LICENSE file.
 */

namespace SprykerUFirst\Zed\SecondFactorAuth;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerUFirst\Shared\SecondFactorAuth\SecondFactorAuthConstants;

class SecondFactorAuthConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const SECOND_FACTOR_URL = '/second-factor-auth/authentication';

    /**
     * @var string
     */
    public const URL_REGISTRATION = '/second-factor-auth/registration';

    /**
     * @var string
     */
    public const URL_REGISTRATION_REGISTER = '/second-factor-auth/registration/register';

    /**
     * @var string
     */
    public const URL_REGISTRATION_UNREGISTER = '/second-factor-auth/registration/unregister';

    /**
     * @var string
     */
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
     * @var array<string>
     */
    protected $ignorableUsers = [];

    /**
     * @api
     *
     * @return string
     */
    public function getSecondFactorAuthUrl(): string
    {
        return static::SECOND_FACTOR_URL;
    }

    /**
     * @api
     *
     * @return array
     */
    public function getIgnorablePaths(): array
    {
        return $this->ignorablePaths;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getIgnorableUsers(): array
    {
        $ignorableUsers = array_merge($this->ignorableUsers, $this->get(SecondFactorAuthConstants::SECOND_FACTOR_AUTH_IGNORABLE_USERS, []));

        return $ignorableUsers;
    }

    /**
     * @api
     *
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
     * @api
     *
     * @param string $username
     *
     * @return void
     */
    public function addIgnorableUser(string $username): void
    {
        $this->ignorableUsers[] = $username;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getHomePath(): string
    {
        return static::HOME_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getSecondFactorAuthRegistrationUrl(): string
    {
        return static::URL_REGISTRATION;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getHostname(): string
    {
        return $this->get(ApplicationConstants::BASE_URL_ZED);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getIsSecondFactorAuthRequired(): bool
    {
        return $this->get(SecondFactorAuthConstants::SECOND_FACTOR_AUTH_REQUIRED, false);
    }
}
