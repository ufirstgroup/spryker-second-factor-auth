<?php

/**
 * MIT License
 * See LICENSE file.
 */

namespace SprykerUFirst\Zed\SecondFactorAuth\Communication\Plugin\Table;

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
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $item): array
    {
        $item[SecondFactorAuthUserTableConfigExpanderPlugin::SECOND_FACTOR_AUTH_STATUS] = $this->createSecondFAStatusLabel($item);
        $item[SecondFactorAuthUserTableConfigExpanderPlugin::SECOND_FACTOR_AUTH_RESET] = $this->createSecondFAResetButton($item);

        return $item;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $user
     *
     * @return string
     */
    public function createSecondFAStatusLabel(array $user): string
    {
        $userIsRegistered = $this->getRepository()->doesUserHaveSecret($user[SpyUserTableMap::COL_ID_USER]);

        if ($userIsRegistered) {
            return '<span class="label label-success" title="Active">Active</span>';
        }

        return '<span class="label label-danger" title="Deactivated">Deactivated</span>';
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $user
     *
     * @return string
     */
    public function createSecondFAResetButton(array $user): string
    {
        $userIsRegistered = $this->getRepository()->doesUserHaveSecret($user[SpyUserTableMap::COL_ID_USER]);

        $buttonHTML = '<a href="/second-factor-auth/user/unregister?id-user=' . $user[SpyUserTableMap::COL_ID_USER] . '" class="btn btn-xs btn-outline btn-danger">Reset 2fa</a>';

        if ($userIsRegistered) {
            return $buttonHTML;
        }

        return '<span class="btn btn-xs btn-outline btn-view disabled">Reset 2fa</span>';
    }
}