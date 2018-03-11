<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12.02.2018
 * Time: 21:55
 */

namespace App\ValidationHelper;

use App\Entity\AdminUsers;
use App\Repository\AdminUsersRepository;
use Doctrine\ORM\EntityManagerInterface;

class AuthValidationHelper implements ValidationHelperInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
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
    public function checkAuthUser($userId, $sessionKey)
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