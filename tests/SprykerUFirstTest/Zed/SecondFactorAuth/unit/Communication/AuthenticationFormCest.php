<?php

namespace SprykerUFirstTest\Zed\SecondFactorAuth\unit\Communication\Form;

use SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester;
use SprykerUFirst\Zed\SecondFactorAuth\Communication\Form\AuthenticationForm;
use SprykerUFirstTest\Zed\CompanyGui\CompanyGuiUnitTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerUFirstTest
 * @group Zed
 * @group SecondFactorAuth
 * @group unit
 * @group Business
 * @group Communication
 * @group Form
 * @group AuthenticationFormCest
 * Add your own group annotations below this line
 */
class AuthenticationFormCest
{
    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testBuildForm(SecondFactorAuthUnitTester $I): void
    {
        $I->wantTo('make sure the form fields exist in the builder');
        $SUT = new AuthenticationForm();

        $formBuilder = $I->createFormBuilder();

        $SUT->buildForm($formBuilder, []);

        $I->assertTrue($formBuilder->has(AuthenticationForm::FIELD_CODE));
        $I->assertTrue($formBuilder->has(AuthenticationForm::FIELD_TRUST_DEVICE));
    }
}
