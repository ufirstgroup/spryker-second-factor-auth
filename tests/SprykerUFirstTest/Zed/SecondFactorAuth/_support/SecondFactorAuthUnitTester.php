<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerUFirstTest\Zed\SecondFactorAuth;

use Codeception\Actor;
use Codeception\Scenario;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;
use Spryker\Shared\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class SecondFactorAuthUnitTester extends Actor
{
    use _generated\SecondFactorAuthUnitTesterActions;

    /**
     * @var \Prophecy\Prophet
     */
    private $prophet;

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        $this->prophet = new Prophet();
        parent::__construct($scenario);
    }

    /**
     * Creates new object prophecy.
     *
     * @param string|null $classOrInterface Class or interface name
     *
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    public function prophesize(?string $classOrInterface = null): ObjectProphecy
    {
        return $this->prophet->prophesize($classOrInterface);
    }

    /**
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function createFormBuilder(): FormBuilderInterface
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
