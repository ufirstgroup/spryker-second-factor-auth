<?php

/**
 * MIT License
 * See LICENSE file.
 */

namespace SprykerUFirst\Zed\SecondFactorAuth\Communication\Plugin\Table;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserTableConfigExpanderPluginInterface;

/**
 * @method \SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthConfig getConfig()
 */
class SecondFactorAuthUserTableConfigExpanderPlugin extends AbstractPlugin implements UserTableConfigExpanderPluginInterface
{
    /**
     * @var string
     */
    public const SECOND_FACTOR_AUTH_STATUS = '2fa status';

    /**
     * @var string
     */
    public const SECOND_FACTOR_AUTH_RESET = 'reset 2fa';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        $header = $config->getHeader();
        $config->addRawColumn(static::SECOND_FACTOR_AUTH_STATUS);
        $header = $this->addAfterPosition($header, 5, [static::SECOND_FACTOR_AUTH_STATUS => static::SECOND_FACTOR_AUTH_STATUS]);

        if ($this->getConfig()->getShouldShowSecondFAReset()) {
            $config->addRawColumn(static::SECOND_FACTOR_AUTH_RESET);
            $header = $this->addAfterPosition($header, 6, [static::SECOND_FACTOR_AUTH_RESET => static::SECOND_FACTOR_AUTH_RESET]);
        }
        $config->setHeader($header);

        return $config;
    }

    /**
     * @param array $array
     * @param int $position
     * @param array $element
     *
     * @return array
     */
    private function addAfterPosition(array $array, int $position, array $element): array
    {
        return array_slice($array, 0, $position, true) +
            $element +
            array_slice($array, $position, count($array) - $position, true);
    }
}