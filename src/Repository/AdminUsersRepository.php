<?php

namespace App\Repository;

use App\Entity\AdminUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AdminUsersRepository extends ServiceEntityRepository
{
    const PROPERTY_ID = 'id';
    const PROPERTY_LOGIN = 'login';
    const PROPERTY_PASSWORD = 'password';
    const PROPERTY_SESSION_KEY = 'sessionKey';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdminUsers::class);
    }

    /**
     * @param string $login
     * @param string $password
     * @return array
     */
    public function getIdByLoginAndPassword(string $login, string $password)
    {
        return $this->createQueryBuilder('au')
            ->select('au.id')
            ->where('au.login = :login')
            ->andWhere('au.password = :password')
            ->setParameters([
                'login' => $login,
                'password' => $password
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $userId
     * @param string $sessionKey
     * @return mixed
     */
    public function updateSessionKeyByUserId(int $userId, string $sessionKey)
    {
        return $this->createQueryBuilder('au')
            ->update(AdminUsers::class, 'au')
            ->set('au.sessionKey', ':sessionKey')
            ->where('au.id = :userId')
            ->setParameters([
                'sessionKey' => $sessionKey,
                'userId' => $userId
            ])
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function getSessionKeyByUserId(int $userId)
    {
        return $this->createQueryBuilder('au')
            ->select('au.sessionKey')
            ->where('au.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('a')
            ->where('a.something = :value')->setParameter('value', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
