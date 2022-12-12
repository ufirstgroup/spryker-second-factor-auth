<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerUFirstTest\Zed\SecondFactorAuth\unit\Communication\Form;

use Spryker\Shared\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;

abstract class AbstractFormCest
{
    /**
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function createFormBuilder(): FormBuilderInterface
    {
        $formFactory = $this->prophecyHelper->prophesize(FormFactoryInterface::class);
        $eventDispatcher = $this->prophecyHelper->prophesize(EventDispatcher::class);

        $formBuilder = new FormBuilder(
            'form',
            null,
            $eventDispatcher->reveal(),
            $formFactory->reveal(),
            [],
        );

        return $formBuilder;
    }
}
