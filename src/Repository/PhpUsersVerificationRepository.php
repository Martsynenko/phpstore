<?php

namespace App\Repository;

use App\Entity\PhpUsersVerification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpUsersVerification|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpUsersVerification|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpUsersVerification[]    findAll()
 * @method PhpUsersVerification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpUsersVerificationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpUsersVerification::class);
    }

    /**
     * @param int $userId
     * @param string $url
     * @param string $hash
     */
    public function insertUserVerificationData($userId, $url, $hash)
    {
        $entityManager = $this->getEntityManager();

        $userVerification = new PhpUsersVerification();
        $userVerification->setUserId($userId);
        $userVerification->setPageReferer($url);
        $userVerification->setHash($hash);

        $entityManager->persist($userVerification);
        $entityManager->flush();
    }

    /**
     * @param string $hash
     * @return mixed
     */
    public function getVerificationDataByHash($hash)
    {
        return $this->findBy(['hash' => $hash]);
//        return $this->createQueryBuilder('puv')
//            ->select('puv.userId, puv.pageReferer')
//            ->where('puv.hash = :hash')
//            ->setParameter('hash', $hash)
//            ->getQuery()
//            ->getArrayResult();
    }

    /**
     * @param PhpUsersVerification $usersVerification
     */
    public function deleteUserVerification(PhpUsersVerification $usersVerification)
    {
        $this->_em->remove($usersVerification);
        $this->_em->flush();
    }
}
