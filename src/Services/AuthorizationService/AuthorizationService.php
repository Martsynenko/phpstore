<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 19.05.2018
 * Time: 15:01
 */

namespace App\Services\AuthorizationService;

use App\Controller\Admin\LoginController;
use App\Entity\AdminUsers;
use App\Repository\AdminUsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthorizationService extends Controller implements AuthorizationServiceInterface
{
    /** @var EntityManagerInterface $em */
    protected $em;

    /** @var  SessionInterface $session */
    protected $session;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->em = $entityManager;
        $this->session = $session;
    }

    public function checkAuth()
    {
        $sessionKey = $this->session->get(LoginController::SESSION_SESSION_KEY);
        $userId = $this->session->get(LoginController::SESSION_USER_ID);
        if (!$this->checkAuthUser($userId, $sessionKey)) {
            header("Location: http://phpstore.ua/wde-master/admin/login/"); /* Перенаправление браузера */
            exit;
        }
    }

    /**
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function checkIssetUser(string $login, string $password)
    {
        $password = md5($password);

        $userId = $this->em->getRepository(AdminUsers::class)->getIdByLoginAndPassword($login, $password);

        if (!empty($userId)) {
            return $userId[AdminUsersRepository::PROPERTY_ID];
        }

        return false;
    }

    /**
     * @param int $userId
     * @param string $key
     */
    public function setSessionKey(int $userId, string $key)
    {
        $this->em->getRepository(AdminUsers::class)->updateSessionKeyByUserId($userId, $key);
    }

    /**
     * @param mixed $userId
     * @param mixed $sessionKey
     * @return bool
     */
    private function checkAuthUser($userId, $sessionKey)
    {
        if (empty($sessionKey) || empty($userId)) {
            return false;
        }

        $sessionKeyFromDB = $this->em->getRepository(AdminUsers::class)->getSessionKeyByUserId($userId);

        if ($sessionKey != $sessionKeyFromDB[AdminUsersRepository::PROPERTY_SESSION_KEY]) {
            return false;
        }

        return true;
    }
}