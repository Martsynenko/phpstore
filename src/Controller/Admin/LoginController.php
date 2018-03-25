<?php

namespace App\Controller\Admin;

use App\Entity\AdminUsers;
use App\Repository\AdminUsersRepository;
use App\ValidationHelper\AuthValidationHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginController extends Controller
{
    const SESSION_SESSION_KEY = 'sessionKey';
    const SESSION_USER_ID = 'userId';

    /**
     * @Route("/wde-master/admin/login", name="admin-login")
     */
    public function index()
    {
        return $this->render('admin/login/login.html.twig');
    }

    /**
     * @Route("/wde-master/admin/auth", name="admin-auth")
     * @param Request $request
     * @param SessionInterface $session
     * @param AuthValidationHelper $authValidationHelper
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function auth(
        Request $request,
        SessionInterface $session,
        AuthValidationHelper $authValidationHelper
    )
    {
        $session->clear();

        $login = $request->get(AdminUsersRepository::PROPERTY_LOGIN);
        $password = $request->get(AdminUsersRepository::PROPERTY_PASSWORD);

        $userId = $authValidationHelper->checkIssetUser($login, $password);
        if (!$userId) {
            $this->addFlash('notice', 'Wrong name or password!!!');
            return $this->redirectToRoute('admin-login');
        }

        $sessionKey = md5(uniqid(rand(), true));

        $authValidationHelper->setSessionKey($userId, $sessionKey);

        $session->set(self::SESSION_SESSION_KEY, $sessionKey);
        $session->set(self::SESSION_USER_ID, $userId);

        return $this->redirectToRoute('admin-home');
    }

    /**
     * @Route("/wde-master/admin/logout", name="admin-logout")
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function logout(SessionInterface $session)
    {
        $session->clear();

        return $this->redirectToRoute('admin-login');
    }
}
