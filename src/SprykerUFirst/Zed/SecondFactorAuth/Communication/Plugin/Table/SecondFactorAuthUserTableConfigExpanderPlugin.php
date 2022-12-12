<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerUFirst\Zed\SecondFactorAuth\Communication\Plugin\Table;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserTableConfigExpanderPluginInterface;

class SecondFactorAuthUserTableConfigExpanderPlugin implements UserTableConfigExpanderPluginInterface
{
    /**
     * @var string
     */
    public const SECOND_FACTOR_AUTH_STATUS = '2fa status';

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
        $header = $this->addAfterPosition($header, 5, [static::SECOND_FACTOR_AUTH_STATUS => static::SECOND_FACTOR_AUTH_STATUS]);
        $config->setHeader($header);

        $config->addRawColumn(static::SECOND_FACTOR_AUTH_STATUS);

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
