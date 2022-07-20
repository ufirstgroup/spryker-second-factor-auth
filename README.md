# Second Factor Authentication for the Spryker Administration Interface

## Installation

```
composer require spryker-ufirst/second-factor-auth
```

Once the module is installed, add the `SecondFactorAuthorizationEventDispatcherPlugin` to the
`EventDispatcherDependencyProvider::getEventDispatcherPlugins` at the end of the array:

```php
class EventDispatcherDependencyProvider extends SprykerEventDispatcherDependencyProvider
{
    /**
     * @return \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface[]
     */
    protected function getEventDispatcherPlugins(): array
    {
        return [
            # ...other plugins
            new SecondFactorAuthorizationEventDispatcherPlugin(),
        ];
    }
}
```

And add the `SprykerUFirst` namespace to the `config_default.php`

```php
$config[KernelConstants::CORE_NAMESPACES] = [
    ...
    'SprykerUFirst',
];
```

## Enforcing 2FA

You can enforce second factor authentication for all admin users per environment. Add the following line to your
`config_default.php`:

```php
use SprykerUFirst\Shared\SecondFactorAuth\SecondFactorAuthConstants;
...
$config[SecondFactorAuthConstants::SECOND_FACTOR_AUTH_REQUIRED] = true;
```

## Add 2FA Status to the User Table

In order to see the 2FA status (enabled/disabled) on each user in the administration GUI, add the two table expander
plugins to the `UserDependencyProvider` in your project space:

```php
<?php

namespace Pyz\Zed\User;

use Spryker\Zed\User\UserDependencyProvider as SprykerUserDependencyProvider;
use SprykerUFirst\Zed\SecondFactorAuth\Communication\Plugin\Table\SecondFactorAuthUserTableConfigExpanderPlugin;
use SprykerUFirst\Zed\SecondFactorAuth\Communication\Plugin\Table\SecondFactorAuthUserTableDataExpanderPlugin;

class UserDependencyProvider extends SprykerUserDependencyProvider
{

    /**
     * @return \Spryker\Zed\UserExtension\Dependency\Plugin\UserTableDataExpanderPluginInterface[]
     */
    protected function getUserTableDataExpanderPlugins(): array
    {
        return [
            new SecondFactorAuthUserTableDataExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\UserExtension\Dependency\Plugin\UserTableConfigExpanderPluginInterface[]
     */
    protected function getUserTableConfigExpanderPlugins(): array
    {
        return [
            new SecondFactorAuthUserTableConfigExpanderPlugin(),
        ];
    }
}
```
