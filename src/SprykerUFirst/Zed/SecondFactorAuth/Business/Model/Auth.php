<?php

namespace SprykerUFirst\Zed\SecondFactorAuth\Business\Model;

use DateTime;
use Exception;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\SecondFactorAuth\Persistence\SpyUfgSecondFactorAuthTrustedDevice;
use SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthEntityManagerInterface;
use SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthRepositoryInterface;
use SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthConfig;
use PragmaRX\Google2FA\Google2FA;
use Spryker\Shared\Auth\AuthConstants;
use Spryker\Zed\User\Business\UserFacadeInterface;

class Auth
{
    /**
     * @var \Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthConfig
     */
    protected $config;

    /**
     * @var \PragmaRX\Google2FA\Google2FA;
     */
    protected $googleAuthenticator;

    /**
     * @var \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthRepositoryInterface
     */
    protected $repository;

    /**
     * @var \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\User\Business\UserFacadeInterface $userFacade
     * @param \SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthConfig $config
     * @param \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthRepositoryInterface $repository
     * @param PragmaRX\Google2FA\Google2FA $googleAuthenticator
     * @param \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthEntityManagerInterface $entityManager
     */
    public function __construct(
        UserFacadeInterface $userFacade,
        SecondFactorAuthConfig $config,
        SecondFactorAuthRepositoryInterface $repository,
        Google2FA $googleAuthenticator,
        SecondFactorAuthEntityManagerInterface $entityManager
    ) {
        $this->userFacade = $userFacade;
        $this->config = $config;
        $this->repository = $repository;
        $this->entityManager = $entityManager;

        $this->googleAuthenticator = $googleAuthenticator;
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public function authenticate(string $code): bool
    {
        if (!$this->userFacade->hasCurrentUser()) {
            return false;
        }

        $userTransfer = $this->getCurrentUser();
        $secret = $userTransfer->getSecondFactorAuthSecret();
        if (!$this->googleAuthenticator->verifyKey($secret, $code)) {
            return false;
        }

        $userTransfer->setSecondFactorIsAuthenticated(true);
        $this->userFacade->setCurrentUser($userTransfer);

        $this->collectTrustedDeviceGarbage($userTransfer);

        return true;
    }

    /**
     * @codeCoverageIgnore Reason: talks to DB directly - needs refactoring first...
     *
     * @param string $device
     *
     * @return void
     */
    public function trustDevice(string $device): void
    {
        if (!$this->userFacade->hasCurrentUser()) {
            return;
        }
        $userTransfer = $this->getCurrentUser();

        $trustedDeviceEntity = new SpyUfgSecondFactorAuthTrustedDevice();
        $trustedDeviceEntity
            ->setDevice($device)
            ->setFkUser($userTransfer->getIdUser());
        $trustedDeviceEntity->save();
    }

    /**
     * @param string|null $device
     *
     * @return bool
     */
    public function isAuthenticated(?string $device): bool
    {
        $userTransfer = $this->getCurrentUser();
        if ($userTransfer->getSecondFactorIsAuthenticated() === true) {
            return true;
        }

        if ($device !== null && $this->isTrustedDevice($userTransfer, $device)) {
            $userTransfer->setSecondFactorIsAuthenticated(true);
            $this->userFacade->setCurrentUser($userTransfer);

            return true;
        }

        return false;
    }

    /**
     * @codeCoverageIgnore reason: no logic
     *
     * @return string
     */
    public function createSecret(): string
    {
        $secret = $this->googleAuthenticator->generateSecretKey();

        return $secret;
    }

    /**
     * @codeCoverageIgnore reason: no logic
     *
     * @param string $secret
     *
     * @return string
     */
    public function getUrl(string $secret): string
    {
        $hostname = $this->config->getHostname();
        $userTransfer = $this->loadCurrentUser();

        return $this->googleAuthenticator->getQRCodeUrl($hostname, $userTransfer->getUsername(), $secret);
    }

    /**
     * @param string $secret
     * @param string $code
     *
     * @return bool
     */
    public function registerCurrentUser(string $secret, string $code): bool
    {
        if (!$this->googleAuthenticator->verifyKey($secret, $code)) {
            return false;
        }
        $currentUserTransfer = $this->getCurrentUser();

        $currentUserTransfer
            ->setSecondFactorAuthSecret($secret)
            ->setSecondFactorIsAuthenticated(true);

        $this->entityManager->saveSecret($currentUserTransfer);
        $this->userFacade->setCurrentUser($currentUserTransfer);

        return true;
    }

    /**
     * @param int|null $idUser
     *
     * @return void
     */
    public function unregisterUser(?int $idUser = null): void
    {
        $currentUserTransfer = $idUser !== null ? $this->userFacade->getUserById($idUser) : null;
        $userTransfer = $this->loadCurrentUser($currentUserTransfer);
        if ($this->checkIsUserRegistered($userTransfer)) {
            $this->entityManager->deleteSecret($userTransfer);
            $this->entityManager->removeAllTrustedDevicesForUser($userTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer|null $currentUserTransfer
     *
     * @return bool
     */
    public function isUserRegistered(?UserTransfer $currentUserTransfer = null): bool
    {
        $userTransfer = $this->loadCurrentUser($currentUserTransfer);

        return $this->checkIsUserRegistered($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    private function checkIsUserRegistered(UserTransfer $userTransfer): bool
    {
        return $userTransfer->getSecondFactorAuthSecret() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer|null $currentUserTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    private function loadCurrentUser(?UserTransfer $currentUserTransfer = null): UserTransfer
    {
        if ($currentUserTransfer === null) {
            $currentUserTransfer = $this->userFacade->getCurrentUser();
        }

        $currentUserTransfer = $this->userFacade->getUserById($currentUserTransfer->getIdUser());
        $this->repository->addSecretToUserTransfer($currentUserTransfer);

        return $currentUserTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    private function getCurrentUser(): UserTransfer
    {
        $currentUserTransfer = $this->userFacade->getCurrentUser();
        $this->repository->addSecretToUserTransfer($currentUserTransfer);

        return $currentUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     * @param string $device
     *
     * @return bool
     */
    private function isTrustedDevice(UserTransfer $user, string $device): bool
    {
        $trustedDeviceQuery = $this->repository->getTrustedDeviceByUserIdAndDeviceId($user->getIdUser(), $device);
        $trustedDevice = $trustedDeviceQuery->findOne();
        if ($trustedDevice === null) {
            return false;
        }

        return ($trustedDevice->getCreatedAt()->modify('+2 weeks') > new DateTime());
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    private function collectTrustedDeviceGarbage(UserTransfer $userTransfer): void
    {
        try {
            $this->entityManager->collectTrustedDeviceGarbage($userTransfer);
        } catch (Exception $e) { // @codeCoverageIgnore
            # Don't fail in the GC.
        }
    }

    /**
     * @param string|null $bundle
     * @param string|null $controller
     * @param string|null $action
     *
     * @return bool
     */
    public function isIgnorablePath(?string $bundle, ?string $controller, ?string $action): bool
    {
        $ignorable = $this->config->getIgnorable();
        foreach ($ignorable as $ignore) {
            if (($bundle === $ignore['bundle'] || $ignore['bundle'] === AuthConstants::AUTHORIZATION_WILDCARD) &&
                ($controller === $ignore['controller'] || $ignore['controller'] === AuthConstants::AUTHORIZATION_WILDCARD) &&
                ($action === $ignore['action'] || $ignore['action'] === AuthConstants::AUTHORIZATION_WILDCARD)
            ) {
                return true;
            }
        }

        return false;
    }
}
