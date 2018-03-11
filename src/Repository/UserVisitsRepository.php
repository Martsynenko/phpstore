<?php

namespace App\Repository;

use App\Entity\UserVisits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserVisitsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserVisits::class);
    }

    /**
     * @param int $month
     * @return mixed
     */
    public function getUserVisitsByDate($month)
    {
        return $this->createQueryBuilder('uv')
            ->select('DAY(uv.date) as day, COUNT(uv.id) as count')
            ->where('MONTH(uv.date) = :month')
            ->groupBy('uv.date')
            ->setParameters([
                'month' => $month
            ])
            ->getQuery()
            ->getResult();
    }
}
