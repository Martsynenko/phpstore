<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18.05.2018
 * Time: 13:41
 */

namespace App\Controller\Admin;

use App\ValidationHelper\AuthValidationHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

abstract class AbstractController extends Controller
{
    /**
     * AbstractController constructor.
     * @param AuthValidationHelper $authValidationHelper
     * @param SessionInterface $session
     */
    public function __construct(
        AuthValidationHelper $authValidationHelper,
        SessionInterface $session
    ) {
        $sessionKey = $session->get(LoginController::SESSION_SESSION_KEY);
        $userId = $session->get(LoginController::SESSION_USER_ID);
        if (!$authValidationHelper->checkAuthUser($userId, $sessionKey)) {
            return $this->redirectToRoute('admin-login');
        }
    }
}