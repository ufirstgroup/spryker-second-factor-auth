<?php

namespace SprykerUFirst\Zed\SecondFactorAuth\Communication\Plugin\Table;

use Orm\Zed\SecondFactorAuth\Persistence\Map\SpyUfgSecondFactorAuthSecretTableMap;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserTableDataExpanderPluginInterface;

/**
 * Class SecondFactorAuthUserTableDataExpanderPlugins
 *
 * @package SprykerUFirst\Zed\SecondFactorAuth\Communication\Plugin\Table
 *
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthRepositoryInterface getRepository()
 */
class SecondFactorAuthUserTableDataExpanderPlugin extends AbstractPlugin implements UserTableDataExpanderPluginInterface
{
    /**
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $item): array
    {
        $item[SecondFactorAuthUserTableConfigExpanderPlugin::SECOND_FACTOR_AUTH_STATUS] = $this->createSecondFAStatusLabel($item);

        return $item;
    }

    /**
     * @param array $user
     *
     * @return string
     */
    public function createSecondFAStatusLabel(array $user)
    {
        $userIsRegistered = $this->getRepository()->doesUserHaveSecret($user[SpyUserTableMap::COL_ID_USER]);

        if ($userIsRegistered) {
            return '<span class="label label-success" title="Active">Active</span>';
        }

        return '<span class="label label-danger" title="Deactivated">Deactivated</span>';
    }
}
