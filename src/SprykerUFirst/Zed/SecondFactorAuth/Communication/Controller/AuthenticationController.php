<?php

namespace SprykerUFirst\Zed\SecondFactorAuth\Communication\Controller;

use DateTime;
use SprykerUFirst\Shared\SecondFactorAuth\SecondFactorAuthConstants;
use SprykerUFirst\Zed\SecondFactorAuth\Communication\Form\AuthenticationForm;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CheckController
 *
 * @package SprykerUFirst\Zed\SecondFactorAuth\Communication\Controller
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Communication\SecondFactorAuthCommunicationFactory getFactory()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Business\SecondFactorAuthFacadeInterface getFacade()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthRepositoryInterface getQueryContainer()
 */
class AuthenticationController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $currentDeviceCookie = $request->cookies->get(SecondFactorAuthConstants::SECOND_FACTOR_AUTH_DEVICE_COOKIE_NAME);
        if ($this->getFacade()->isAuthenticated($currentDeviceCookie)) {
            return $this->redirectResponse(AuthConfig::DEFAULT_URL_REDIRECT);
        }

        $form = $this
            ->getFactory()
            ->createAuthenticationForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $isAuthenticated = $this->getFacade()->authenticate(
                $formData[AuthenticationForm::FIELD_CODE]
            );

            if ($isAuthenticated) {
                $redirectResponse = $this->redirectResponse(AuthConfig::DEFAULT_URL_REDIRECT);

                if ($formData[AuthenticationForm::FIELD_TRUST_DEVICE]) {
                    $trustedDeviceCookie = Uuid::uuid1()->toString();
                    $this->getFacade()->trustDevice($trustedDeviceCookie);
                    $redirectResponse->headers->setCookie(
                        new Cookie(
                            SecondFactorAuthConstants::SECOND_FACTOR_AUTH_DEVICE_COOKIE_NAME,
                            $trustedDeviceCookie,
                            (new DateTime())->modify('+2 weeks')
                        )
                    );
                }

                return $redirectResponse;
            }

            $this->addErrorMessage('Authentication failed!');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }
}
