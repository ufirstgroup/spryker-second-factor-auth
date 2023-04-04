<?php

/**
 * MIT License
 * See LICENSE file.
 */

namespace SprykerUFirst\Shared\SecondFactorAuth;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SecondFactorAuthConstants
{
    /**
     * @var string
     */
    public const SECOND_FACTOR_AUTH_REQUIRED = 'SECOND_FACTOR_AUTH_REQUIRED';

    /**
     * @var string
     */
    public const SECOND_FACTOR_AUTH_DEVICE_COOKIE_NAME = 'sfadevice';

    /**
     * @var string
     */
    public const SECOND_FACTOR_AUTH_IGNORABLE_USERS = 'SECOND_FACTOR_AUTH_IGNORABLE_USERS';

    /**
     * @var string
     */
    public const SHOW_SECOND_FACTOR_AUTH_RESET = 'SHOW_SECOND_FACTOR_AUTH_RESET';
}