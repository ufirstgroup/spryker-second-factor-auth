<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerUFirstTest\Zed\SecondFactorAuth\unit\Communication\Form;

use SprykerUFirst\Zed\SecondFactorAuth\Communication\Form\RegistrationForm;
use SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group SecondFactorAuth
 * @group unit
 * @group Business
 * @group Communication
 * @group Form
 * @group RegistrationFormCest
 * Add your own group annotations below this line
 */
class RegistrationFormCest
{
    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testBuildForm(SecondFactorAuthUnitTester $I): void
    {
        $I->wantTo('make sure the form fields exist in the builder');
        $SUT = new RegistrationForm();

        $formBuilder = $I->createFormBuilder();

        $SUT->buildForm($formBuilder, ['secret' => 'some_secret']);

        $I->assertTrue($formBuilder->has(RegistrationForm::FIELD_CODE));
    }
}
