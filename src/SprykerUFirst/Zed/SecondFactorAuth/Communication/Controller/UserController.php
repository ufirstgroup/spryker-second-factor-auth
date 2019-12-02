<?php

namespace SprykerUFirst\Zed\SecondFactorAuth\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 *
 * @package SprykerUFirst\Zed\SecondFactorAuth\Communication\Controller
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Communication\SecondFactorAuthCommunicationFactory getFactory()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Business\SecondFactorAuthFacadeInterface getFacade()
 * @method \SprykerUFirst\Zed\SecondFactorAuth\Persistence\SecondFactorAuthRepositoryInterface getQueryContainer()
 */
class UserController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function unregisterAction(Request $request)
    {
        $idUser = $request->get('id-user');
        if (empty($idUser)) {
            $this->addErrorMessage('You have to pass a user!');
        } else {
            $this->getFacade()->unregisterUser($idUser);
            $this->addSuccessMessage('User is unregistered.');
        }
        return $this->redirectResponse('/user');
    }
}
