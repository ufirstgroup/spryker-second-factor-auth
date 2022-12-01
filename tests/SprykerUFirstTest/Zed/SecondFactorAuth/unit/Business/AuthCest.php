<?php

namespace SprykerUFirstTest\Zed\SecondFactorAuth\unit\Business\Model;

use DateTime;
use Generated\Shared\DataBuilder\UserBuilder;
use Orm\Zed\SecondFactorAuth\Persistence\SpyUfgSecondFactorAuthTrustedDevice;
use Orm\Zed\SecondFactorAuth\Persistence\SpyUfgSecondFactorAuthTrustedDeviceQuery;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;
use SprykerUFirst\Zed\SecondFactorAuth\Business\Model\Auth;
use SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthEntityManagerInterface;
use SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthRepositoryInterface;
use SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthConfig;
use SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester;
use Sonata\GoogleAuthenticator\GoogleAuthenticatorInterface;
use Spryker\Zed\User\Business\UserFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerUFirstTest
 * @group Zed
 * @group SecondFactorAuth
 * @group unit
 * @group Business
 * @group Model
 * @group AuthCest
 * Add your own group annotations below this line
 */
class AuthCest
{
    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testAuthenticateNoUser(SecondFactorAuthUnitTester $I): void
    {
        $I->wantToTest('Authentication fails if there is no current user (login first, then second factor).');

        # Data:
        $fakeCode = 'fake_code';

        # Prophecies:
        $userFacade = $I->prophesize(UserFacadeInterface::class);
        $secondFactorAuthConfig = $I->prophesize(SecondFactorAuthConfig::class);
        $secondFactorRepository = $I->prophesize(SecondFactorAuthRepositoryInterface::class);
        $googleAuthenticator = $I->prophesize(GoogleAuthenticatorInterface::class);
        $secondFactoryEntityManager = $I->prophesize(SecondFactorAuthEntityManagerInterface::class);

        # system under test:
        $SUT = new Auth(
            $userFacade->reveal(),
            $secondFactorAuthConfig->reveal(),
            $secondFactorRepository->reveal(),
            $googleAuthenticator->reveal(),
            $secondFactoryEntityManager->reveal()
        );

        # Mocks:
        $userFacade->hasCurrentUser()->willReturn(false);

        $result = $SUT->authenticate($fakeCode);
        $I->assertFalse($result);
    }

    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testAuthenticateFails(SecondFactorAuthUnitTester $I): void
    {
        $I->wantToTest('Authentication succeeds if he GA library does not verify the given code successfully.');

        # Data:
        $fakeCode = 'fake_code';
        $fakeSecret = 'fake_secret';
        $userTransfer = (new UserBuilder())
            ->seed(['idUser' => 1234, 'secondFactorAuthSecret' => $fakeSecret])
            ->build();

        # Prophecies:
        $userFacade = $I->prophesize(UserFacadeInterface::class);
        $secondFactorAuthConfig = $I->prophesize(SecondFactorAuthConfig::class);
        $secondFactorRepository = $I->prophesize(SecondFactorAuthRepositoryInterface::class);
        $googleAuthenticator = $I->prophesize(GoogleAuthenticatorInterface::class);
        $secondFactoryEntityManager = $I->prophesize(SecondFactorAuthEntityManagerInterface::class);

        # system under test:
        $SUT = new Auth(
            $userFacade->reveal(),
            $secondFactorAuthConfig->reveal(),
            $secondFactorRepository->reveal(),
            $googleAuthenticator->reveal(),
            $secondFactoryEntityManager->reveal()
        );

        # Mocks:
        $userFacade->hasCurrentUser()->willReturn(true);
        $userFacade->getCurrentUser()->willReturn($userTransfer);
        $googleAuthenticator->checkCode($fakeSecret, Argument::any())->willReturn(false);
        $secondFactorRepository->addSecretToUserTransfer($userTransfer)->willReturn($userTransfer);

        $result = $SUT->authenticate($fakeCode);
        $I->assertFalse($result);
    }

    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testAuthenticateSucceeds(SecondFactorAuthUnitTester $I): void
    {
        $I->wantToTest('Authentication succeeds if he GA library verifies the given code successfully.');

        # Data:
        $fakeCode = 'fake_code';
        $fakeSecret = 'fake_secret';
        $userTransfer = (new UserBuilder())
            ->seed(['idUser' => 1234, 'secondFactorAuthSecret' => $fakeSecret])
            ->build();

        # Prophecies:
        $userFacade = $I->prophesize(UserFacadeInterface::class);
        $secondFactorAuthConfig = $I->prophesize(SecondFactorAuthConfig::class);
        $secondFactorRepository = $I->prophesize(SecondFactorAuthRepositoryInterface::class);
        $googleAuthenticator = $I->prophesize(GoogleAuthenticatorInterface::class);
        $secondFactoryEntityManager = $I->prophesize(SecondFactorAuthEntityManagerInterface::class);

        # system under test:
        $SUT = new Auth(
            $userFacade->reveal(),
            $secondFactorAuthConfig->reveal(),
            $secondFactorRepository->reveal(),
            $googleAuthenticator->reveal(),
            $secondFactoryEntityManager->reveal()
        );

        # Mocks:
        $userFacade->hasCurrentUser()->willReturn(true);
        $userFacade->getCurrentUser()->willReturn($userTransfer);
        $userFacade->setCurrentUser(Argument::any())->willReturn();
        $googleAuthenticator->checkCode($fakeSecret, Argument::any())->willReturn(true);
        $secondFactorRepository->addSecretToUserTransfer($userTransfer)->willReturn($userTransfer);
        $secondFactorRepository->getTrustedDevicesByUserId()->willReturn($secondFactorRepository);

        $result = $SUT->authenticate($fakeCode);
        $I->assertTrue($result);
    }

    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testIsAuthenticatedSuccess1(SecondFactorAuthUnitTester $I): void
    {
        $I->wantToTest('the function returns true for previously authenticated users.');

        /**
         * @var \Generated\Shared\Transfer\UserTransfer $userTransferArgumentExpectation
         */
        # Data:
        $userTransfer = (new UserBuilder())
            ->seed(['idUser' => 1234])
            ->build();

        # Prophecies:
        $userFacade = $I->prophesize(UserFacadeInterface::class);
        $secondFactorAuthConfig = $I->prophesize(SecondFactorAuthConfig::class);
        $secondFactorRepository = $I->prophesize(SecondFactorAuthRepositoryInterface::class);
        $googleAuthenticator = $I->prophesize(GoogleAuthenticatorInterface::class);
        $secondFactoryEntityManager = $I->prophesize(SecondFactorAuthEntityManagerInterface::class);

        # Stubs:
        $userFacade->getCurrentUser()->willReturn($userTransfer);

        # Mocks:
        $userTransfer->setSecondFactorIsAuthenticated(true);
        $secondFactorRepository->addSecretToUserTransfer($userTransfer)->willReturn($userTransfer);

        # system under test:
        $SUT = new Auth(
            $userFacade->reveal(),
            $secondFactorAuthConfig->reveal(),
            $secondFactorRepository->reveal(),
            $googleAuthenticator->reveal(),
            $secondFactoryEntityManager->reveal()
        );

        $result = $SUT->isAuthenticated(null);
        $I->assertTrue($result);
    }

    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testIsAuthenticatedSuccess2(SecondFactorAuthUnitTester $I): void
    {
        $I->wantToTest('the function returns true for a given already trusted device.');

        /**
         * @var \Generated\Shared\Transfer\UserTransfer $userTransferArgumentExpectation
         */
        # Data:
        $fakeDevice = 'fake_device';
        $fakeUserId = 1234;
        $userTransfer = (new UserBuilder())
            ->seed(['idUser' => 1234, 'secondFactorIsAuthenticated' => false])
            ->build();

        $trustedDevice = (new SpyUfgSecondFactorAuthTrustedDevice())
            ->setDevice($fakeDevice)
            ->setCreatedAt(new DateTime());

        # Prophecies:
        $userFacade = $I->prophesize(UserFacadeInterface::class);
        $secondFactorAuthConfig = $I->prophesize(SecondFactorAuthConfig::class);
        $secondFactorRepository = $I->prophesize(SecondFactorAuthRepositoryInterface::class);
        $googleAuthenticator = $I->prophesize(GoogleAuthenticatorInterface::class);
        $trustedDeviceQuery = $I->prophesize(SpyUfgSecondFactorAuthTrustedDeviceQuery::class);
        $secondFactoryEntityManager = $I->prophesize(SecondFactorAuthEntityManagerInterface::class);

        # Stubs:
        $userFacade->getCurrentUser()->willReturn($userTransfer);

        # Mocks:
        $trustedDeviceQuery->findOne()->willReturn($trustedDevice);
        $secondFactorRepository->getTrustedDeviceByUserIdAndDeviceId($fakeUserId, $fakeDevice)->willReturn($trustedDeviceQuery);
        $secondFactorRepository->addSecretToUserTransfer($userTransfer)->willReturn($userTransfer);
        $userFacade->setCurrentUser($userTransfer)->shouldBeCalled();

        # system under test:
        $SUT = new Auth(
            $userFacade->reveal(),
            $secondFactorAuthConfig->reveal(),
            $secondFactorRepository->reveal(),
            $googleAuthenticator->reveal(),
            $secondFactoryEntityManager->reveal()
        );

        $result = $SUT->isAuthenticated($fakeDevice);
        $I->assertTrue($result);
    }

    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testIsAuthenticatedFailure(SecondFactorAuthUnitTester $I): void
    {
        $I->wantToTest('the function returns false if not authenticated and no trusted device.');

        /**
         * @var \Generated\Shared\Transfer\UserTransfer $userTransferArgumentExpectation
         */
        # Data:
        $fakeDevice = 'fake_device';
        $fakeUserId = 1234;
        $userTransfer = (new UserBuilder())
            ->seed(['idUser' => 1234, 'secondFactorIsAuthenticated' => false])
            ->build();

        # Prophecies:
        $userFacade = $I->prophesize(UserFacadeInterface::class);
        $secondFactorAuthConfig = $I->prophesize(SecondFactorAuthConfig::class);
        $secondFactorRepository = $I->prophesize(SecondFactorAuthRepositoryInterface::class);
        $googleAuthenticator = $I->prophesize(GoogleAuthenticatorInterface::class);
        $trustedDeviceQuery = $I->prophesize(SpyUfgSecondFactorAuthTrustedDeviceQuery::class);
        $secondFactoryEntityManager = $I->prophesize(SecondFactorAuthEntityManagerInterface::class);

        # Stubs:
        $userFacade->getCurrentUser()->willReturn($userTransfer);

        # Mocks:
        $trustedDeviceQuery->findOne()->willReturn(null);
        $secondFactorRepository->getTrustedDeviceByUserIdAndDeviceId($fakeUserId, $fakeDevice)->willReturn($trustedDeviceQuery);
        $secondFactorRepository->addSecretToUserTransfer($userTransfer)->willReturn($userTransfer);

        # system under test:
        $SUT = new Auth(
            $userFacade->reveal(),
            $secondFactorAuthConfig->reveal(),
            $secondFactorRepository->reveal(),
            $googleAuthenticator->reveal(),
            $secondFactoryEntityManager->reveal()
        );

        $result = $SUT->isAuthenticated($fakeDevice);
        $I->assertFalse($result);
    }

    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testRegisterCurrentUserFailure(SecondFactorAuthUnitTester $I): void
    {
        $I->wantToTest('the function returns false if secret and code dont match.');

        /**
         * @var \Generated\Shared\Transfer\UserTransfer $userTransferArgumentExpectation
         */
        # Data:
        $fakeSecret = 'fake_secret';
        $fakeCode = 'fake_code';

        # Prophecies:
        $userFacade = $I->prophesize(UserFacadeInterface::class);
        $secondFactorAuthConfig = $I->prophesize(SecondFactorAuthConfig::class);
        $secondFactorRepository = $I->prophesize(SecondFactorAuthRepositoryInterface::class);
        $googleAuthenticator = $I->prophesize(GoogleAuthenticatorInterface::class);
        $secondFactoryEntityManager = $I->prophesize(SecondFactorAuthEntityManagerInterface::class);

        $userTransfer = (new UserBuilder())
            ->seed(['idUser' => 1234])
            ->build();

        # Mocks:
        $googleAuthenticator->checkCode($fakeSecret, $fakeCode)->willReturn(false);

        # system under test:
        $SUT = new Auth(
            $userFacade->reveal(),
            $secondFactorAuthConfig->reveal(),
            $secondFactorRepository->reveal(),
            $googleAuthenticator->reveal(),
            $secondFactoryEntityManager->reveal()
        );
        $result = $SUT->registerCurrentUser($fakeSecret, $fakeCode);
        $I->assertFalse($result);
    }

    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testRegisterCurrentUserSuccess(SecondFactorAuthUnitTester $I): void
    {
        $I->wantToTest('the function updates the user and sets current user and returns true if secret and code match.');

        /**
         * @var \Generated\Shared\Transfer\UserTransfer $userTransferArgumentExpectation
         */
        # Data:
        $fakeSecret = 'fake_secret';
        $fakeCode = 'fake_code';
        $userTransfer = (new UserBuilder())
            ->seed(['idUser' => 1234])
            ->build();


        # Prophecies:
        $userFacade = $I->prophesize(UserFacadeInterface::class);
        $secondFactorAuthConfig = $I->prophesize(SecondFactorAuthConfig::class);
        $secondFactorRepository = $I->prophesize(SecondFactorAuthRepositoryInterface::class);
        $googleAuthenticator = $I->prophesize(GoogleAuthenticatorInterface::class);
        $secondFactoryEntityManager = $I->prophesize(SecondFactorAuthEntityManagerInterface::class);

        # Mocks:
        $googleAuthenticator->checkCode($fakeSecret, $fakeCode)->willReturn(true);
        $secondFactorRepository->addSecretToUserTransfer($userTransfer)->willReturn($userTransfer);
        $userFacade->getCurrentUser()->willReturn($userTransfer);

        # We expect functions to be called with a UserTransfer object that contains our fakeSecret
        $userTransferArgumentExpectation = Argument::which('getSecondFactorAuthSecret', $fakeSecret);

        # Assert updateUser() is called with a user transfer that meets our expectation above
        $secondFactoryEntityManager->saveSecret($userTransfer)->shouldBeCalled();
        # Assert setCurrentUser() is called with a user transfer that meets our expectation above
        $userFacade->setCurrentUser($userTransferArgumentExpectation)->shouldBeCalled();

        # system under test:
        $SUT = new Auth(
            $userFacade->reveal(),
            $secondFactorAuthConfig->reveal(),
            $secondFactorRepository->reveal(),
            $googleAuthenticator->reveal(),
            $secondFactoryEntityManager->reveal()
        );
        $result = $SUT->registerCurrentUser($fakeSecret, $fakeCode);
        $I->assertTrue($result);
    }

    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testUnregisterDontUnregister(SecondFactorAuthUnitTester $I): void
    {
        $I->wantToTest('the function should not unregister if the user has no secret.');

        # Data:
        $fakeUserId = 1234;
        $userTransfer = (new UserBuilder())
            ->seed([
                'idUser' => $fakeUserId,
                'secondFactorAuthSecret' => null,
            ])
            ->build();

        # Prophecies:
        $userFacade = $I->prophesize(UserFacadeInterface::class);
        $secondFactorAuthConfig = $I->prophesize(SecondFactorAuthConfig::class);
        $secondFactorRepository = $I->prophesize(SecondFactorAuthRepositoryInterface::class);
        $googleAuthenticator = $I->prophesize(GoogleAuthenticatorInterface::class);
        $secondFactoryEntityManager = $I->prophesize(SecondFactorAuthEntityManagerInterface::class);

        # Mocks:
        $userFacade->getCurrentUser()->willReturn($userTransfer);
        $userFacade->getUserById($fakeUserId)->willReturn($userTransfer);
        $secondFactorRepository->addSecretToUserTransfer($userTransfer)->willReturn($userTransfer);

        # Expected calls:
        $secondFactoryEntityManager->deleteSecret($userTransfer)->shouldNotBeCalled();

        # system under test:
        $SUT = new Auth(
            $userFacade->reveal(),
            $secondFactorAuthConfig->reveal(),
            $secondFactorRepository->reveal(),
            $googleAuthenticator->reveal(),
            $secondFactoryEntityManager->reveal()
        );

        $SUT->unregisterUser();
    }

    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testUnregister(SecondFactorAuthUnitTester $I): void
    {
        $I->wantToTest('the function should unregister the user if he has a secret.');

        # Data:
        $fakeUserId = 1234;
        $fakeSecret = 'fake_secret';
        $userTransfer = (new UserBuilder())
            ->seed([
                'idUser' => $fakeUserId,
                'secondFactorAuthSecret' => $fakeSecret,
            ])
            ->build();

        # Prophecies:
        $userFacade = $I->prophesize(UserFacadeInterface::class);
        $secondFactorAuthConfig = $I->prophesize(SecondFactorAuthConfig::class);
        $secondFactorRepository = $I->prophesize(SecondFactorAuthRepositoryInterface::class);
        $googleAuthenticator = $I->prophesize(GoogleAuthenticatorInterface::class);
        $secondFactoryEntityManager = $I->prophesize(SecondFactorAuthEntityManagerInterface::class);

        # Mocks:
        $userFacade->getCurrentUser()->willReturn($userTransfer);
        $userFacade->getUserById($fakeUserId)->willReturn($userTransfer);
        $secondFactorRepository->addSecretToUserTransfer($userTransfer)->willReturn($userTransfer);

        # Expected calls:
        $secondFactoryEntityManager->deleteSecret($userTransfer)->shouldBeCalled();
        $secondFactoryEntityManager->removeAllTrustedDevicesForUser($userTransfer)->shouldBeCalled();

        # system under test:
        $SUT = new Auth(
            $userFacade->reveal(),
            $secondFactorAuthConfig->reveal(),
            $secondFactorRepository->reveal(),
            $googleAuthenticator->reveal(),
            $secondFactoryEntityManager->reveal()
        );

        $SUT->unregisterUser();
    }

    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testIsUserRegisteredUnregisteredWithUser(SecondFactorAuthUnitTester $I): void
    {
        $I->wantToTest('the function returns false if a user with no secret is passed.');

        # Data:
        $fakeUserId = 1234;
        $userTransfer = (new UserBuilder())
            ->seed([
                'idUser' => $fakeUserId,
                'secondFactorAuthSecret' => null,
            ])
            ->build();

        # Prophecies:
        $userFacade = $I->prophesize(UserFacadeInterface::class);
        $secondFactorAuthConfig = $I->prophesize(SecondFactorAuthConfig::class);
        $secondFactorRepository = $I->prophesize(SecondFactorAuthRepositoryInterface::class);
        $googleAuthenticator = $I->prophesize(GoogleAuthenticatorInterface::class);
        $secondFactoryEntityManager = $I->prophesize(SecondFactorAuthEntityManagerInterface::class);

        # Mocks:
        $userFacade->getCurrentUser()->willReturn($userTransfer);
        $userFacade->getUserById($fakeUserId)->willReturn($userTransfer);
        $secondFactorRepository->addSecretToUserTransfer($userTransfer)->willReturn($userTransfer);

        # Expected calls:

        # system under test:
        $SUT = new Auth(
            $userFacade->reveal(),
            $secondFactorAuthConfig->reveal(),
            $secondFactorRepository->reveal(),
            $googleAuthenticator->reveal(),
            $secondFactoryEntityManager->reveal()
        );

        $result = $SUT->isUserRegistered($userTransfer);
        $I->assertFalse($result);
    }

    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testIsUserRegisteredRegisteredNoUser(SecondFactorAuthUnitTester $I): void
    {
        $I->wantToTest('the function returns true if current user has a secret.');

        # Data:
        $fakeUserId = 1234;
        $fakeSecret = 'fake_secret';
        $userTransfer = (new UserBuilder())
            ->seed([
                'idUser' => $fakeUserId,
                'secondFactorAuthSecret' => $fakeSecret,
            ])
            ->build();

        # Prophecies:
        $userFacade = $I->prophesize(UserFacadeInterface::class);
        $secondFactorAuthConfig = $I->prophesize(SecondFactorAuthConfig::class);
        $secondFactorRepository = $I->prophesize(SecondFactorAuthRepositoryInterface::class);
        $googleAuthenticator = $I->prophesize(GoogleAuthenticatorInterface::class);
        $secondFactoryEntityManager = $I->prophesize(SecondFactorAuthEntityManagerInterface::class);

        # Mocks:
        $userFacade->getCurrentUser()->willReturn($userTransfer);
        $userFacade->getUserById($fakeUserId)->willReturn($userTransfer);
        $secondFactorRepository->addSecretToUserTransfer($userTransfer)->willReturn($userTransfer);

        # Expected calls:

        # system under test:
        $SUT = new Auth(
            $userFacade->reveal(),
            $secondFactorAuthConfig->reveal(),
            $secondFactorRepository->reveal(),
            $googleAuthenticator->reveal(),
            $secondFactoryEntityManager->reveal()
        );

        $result = $SUT->isUserRegistered();
        $I->assertTrue($result);
    }

    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testIsIgnorablePath(SecondFactorAuthUnitTester $I): void
    {
        $I->wantToTest('the function returns true or 2fa paths.');

        # Prophecies:
        $userFacade = $I->prophesize(UserFacadeInterface::class);
        $secondFactorAuthConfig = $I->prophesize(SecondFactorAuthConfig::class);
        $secondFactorRepository = $I->prophesize(SecondFactorAuthRepositoryInterface::class);
        $googleAuthenticator = $I->prophesize(GoogleAuthenticatorInterface::class);
        $secondFactoryEntityManager = $I->prophesize(SecondFactorAuthEntityManagerInterface::class);


        # system under test:
        $SUT = new Auth(
            $userFacade->reveal(),
            $secondFactorAuthConfig->reveal(),
            $secondFactorRepository->reveal(),
            $googleAuthenticator->reveal(),
            $secondFactoryEntityManager->reveal()
        );

        $secondFactorAuthConfig->getIgnorablePaths()->willReturn([
            [
                'bundle' => 'some-bundle',
                'controller' => 'some-controller',
                'action' => 'some-action',
            ],
        ]);

        $I->assertTrue($SUT->isIgnorablePath('some-bundle', 'some-controller', 'some-action'));
        $I->assertFalse($SUT->isIgnorablePath('any-budnle', 'any-controller', 'any-action'));
    }

    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testIsUserIgnorableSystemUser(SecondFactorAuthUnitTester $I): void
    {
        $I->wantToTest('the function returns true if a user isSystemUser and idUser is null.');

        # Data:
        $userTransfer = (new UserBuilder())
            ->seed([
                'idUser' => null,
                'isSystemUser' => true,
            ])
            ->build();

        # Prophecies:
        $userFacade = $I->prophesize(UserFacadeInterface::class);
        $secondFactorAuthConfig = $I->prophesize(SecondFactorAuthConfig::class);
        $secondFactorRepository = $I->prophesize(SecondFactorAuthRepositoryInterface::class);
        $googleAuthenticator = $I->prophesize(GoogleAuthenticatorInterface::class);
        $secondFactoryEntityManager = $I->prophesize(SecondFactorAuthEntityManagerInterface::class);

        # Mocks:
        $userFacade->getCurrentUser()->willReturn($userTransfer);

        # Expected calls:

        # system under test:
        $SUT = new Auth(
            $userFacade->reveal(),
            $secondFactorAuthConfig->reveal(),
            $secondFactorRepository->reveal(),
            $googleAuthenticator->reveal(),
            $secondFactoryEntityManager->reveal()
        );

        $result = $SUT->isIgnorableUser($userTransfer);
        $I->assertTrue($result);
    }
    /**
     * @param \SprykerUFirstTest\Zed\SecondFactorAuth\SecondFactorAuthUnitTester $I
     *
     * @return void
     */
    public function testIsUserIgnorableRegularUser(SecondFactorAuthUnitTester $I): void
    {
        $I->wantToTest('the function returns false if a user is not SystemUser and idUser is not null.');

        # Data:
        $fakeUserId = 1234;
        $userTransfer = (new UserBuilder())
            ->seed([
                'idUser' => $fakeUserId,
                'isSystemUser' => null,
            ])
            ->build();

        # Prophecies:
        $userFacade = $I->prophesize(UserFacadeInterface::class);
        $secondFactorAuthConfig = $I->prophesize(SecondFactorAuthConfig::class);
        $secondFactorRepository = $I->prophesize(SecondFactorAuthRepositoryInterface::class);
        $googleAuthenticator = $I->prophesize(GoogleAuthenticatorInterface::class);
        $secondFactoryEntityManager = $I->prophesize(SecondFactorAuthEntityManagerInterface::class);

        # Mocks:
        $userFacade->getCurrentUser()->willReturn($userTransfer);

        # Expected calls:

        # system under test:
        $SUT = new Auth(
            $userFacade->reveal(),
            $secondFactorAuthConfig->reveal(),
            $secondFactorRepository->reveal(),
            $googleAuthenticator->reveal(),
            $secondFactoryEntityManager->reveal()
        );

        $result = $SUT->isIgnorableUser($userTransfer);
        $I->assertFalse($result);
    }
}
