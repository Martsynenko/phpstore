<?php

namespace App\Repository;

use App\Entity\PhpCommentsUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpCommentsUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpCommentsUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpCommentsUsers[]    findAll()
 * @method PhpCommentsUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpCommentsUsersRepository extends ServiceEntityRepository
{
    const VERIFICATION_STATUS_YES = 'yes';
    const VERIFICATION_STATUS_NO = 'no';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpCommentsUsers::class);
    }

    public function getLastInsertUser()
    {
        return $this->createQueryBuilder('pcu')
            ->select('MAX(pcu.id) as id')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param int $userId
     * @param string $status
     */
    public function updateUserStatusVerification($userId, $status)
    {
        $stmt = 'UPDATE `php_comments_users` SET `verification_status` = :status WHERE `id` = :userId';

        $params = [
            'status' => $status,
            'userId' => $userId
        ];

        $this->_em->getConnection()->executeQuery($stmt, $params);
    }
}
