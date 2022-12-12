<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerUFirst\Zed\SecondFactorAuth\Communication\Controller;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use SprykerUFirst\Zed\SecondFactorAuth\Communication\Form\RegistrationForm;
use SprykerUFirst\Zed\SecondFactorAuth\SecondFactorAuthConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RegistrationController
 *
 * @package SprykerUFirst\Zed\SecondFactorAuth\Communication\Controller
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Communication\SecondFactorAuthCommunicationFactory getFactory()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Business\SecondFactorAuthFacadeInterface getFacade()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthRepositoryInterface getQueryContainer()
 */
class RegistrationController extends AbstractController
{
    /**
     * @var string
     */
    public const PARAM_SECRET = 'secret';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     */
    public function indexAction(Request $request)
    {
        if ($this->getFacade()->isUserRegistered()) {
            $form = $this
                ->getFactory()
                ->createUnregistrationForm();

            return $this->viewResponse([
                'status' => 'registered',
                'form' => $form->createView(),
            ]);
        }

        $secret = $this->getFacade()->createSecret();
        $qrCodeUrl = $this->getFacade()->getQrCodeUrl($secret);

        $qrCodeImage = 'data:image/svg+xml;base64,' . $this->generateQrCodeInSvg($qrCodeUrl);

        $form = $this
            ->getFactory()
            ->createRegistrationForm($secret);

        return $this->viewResponse([
            'status' => 'unregistered',
            'is_required' => $this->getFactory()->getConfig()->getIsSecondFactorAuthRequired(),
            'form' => $form->createView(),
            'qr_code_url' => $qrCodeImage,
            'secret' => $secret,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     */
    public function registerAction(Request $request)
    {
        $form = $this
            ->getFactory()
            ->createRegistrationForm('')
            ->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();
            $secret = $formData[RegistrationForm::SECRET];

            $isRegistered = $this->getFacade()->registerCurrentUser(
                $formData[RegistrationForm::SECRET],
                $formData[RegistrationForm::FIELD_CODE],
            );

            if ($isRegistered) {
                $this->addSuccessMessage('You are successfully registered for second factor authentication.');

                return $this->redirectResponse(SecondFactorAuthConfig::URL_REGISTRATION);
            }

            $this->addErrorMessage('The registration failed, please try again.');

            $qrCodeUrl = $this->getFacade()->getQrCodeUrl($secret);

            $qrCodeImage = 'data:image/svg+xml;base64,' . $this->generateQrCodeInSvg($qrCodeUrl);

            $form = $this->getFactory()->createRegistrationForm($secret);

            return $this->viewResponse([
                'status' => 'unregistered',
                'is_required' => $this->getFactory()->getConfig()->getIsSecondFactorAuthRequired(),
                'form' => $form->createView(),
                'qr_code_url' => $qrCodeImage,
                'secret' => $secret,
            ]);
        }

        $this->addErrorMessage('Registration failed!');

        return $this->redirectResponse(SecondFactorAuthConfig::URL_REGISTRATION);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unregisterAction(): RedirectResponse
    {
        $this->getFacade()->unregisterUser();
        $this->addSuccessMessage('Second factor authentication is disabled for your account now.');

        return $this->redirectResponse(SecondFactorAuthConfig::URL_REGISTRATION);
    }

    /**
     * @param string $qrCodeUrl
     *
     * @return string
     */
    protected function generateQrCodeInSvg(string $qrCodeUrl): string
    {
        $writer = new Writer(
            new ImageRenderer(
                new RendererStyle(400),
                new SvgImageBackEnd(),
            ),
        );

        return base64_encode($writer->writeString($qrCodeUrl));
    }
}
