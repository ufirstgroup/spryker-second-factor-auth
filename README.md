# Spryker Second Factor Authentication

## Installation

```
composer install spryker-ufirst/second-factor-auth
```

Once the module is installed, add the `SecondFactorAuthorizationEventDispatcherPlugin` to the
`EventDispatcherDependencyProvider` **after** the  `AuthorizationEventDispatcherPlugin`:

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
            new AuthorizationEventDispatcherPlugin(),
            new SecondFactorAuthorizationEventDispatcherPlugin(),
            # ...other plugins
        ];
    }
}
```

## Enforcing 2FA

You can enforce second factor authentication for all admin users per environment. Add the following line to your
`config_default.php`:

```php
use Pyz\Shared\SecondFactorAuth\SecondFactorAuthConstants;

$config[SecondFactorAuthConstants::SECOND_FACTOR_AUTH_REQUIRED] = true;
```

