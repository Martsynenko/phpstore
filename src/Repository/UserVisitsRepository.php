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

    /**
     * @param $clientIp
     * @param $date
     * @return bool
     */
    public function checkUserVisit($clientIp, $date)
    {
        $stmt = 'SELECT `id` FROM `user_visits` uv 
                 WHERE uv.ip = :clientIp AND uv.date = :date';

        $params = [
            'clientIp' => $clientIp,
            'date' => $date
        ];

        $id = $this->getEntityManager()->getConnection()->executeQuery($stmt, $params)->fetchColumn();

        if ($id) {
            return true;
        }

        return false;
    }

    public function insertUserVisit($clientIp, $date)
    {
        $stmt = 'INSERT INTO `user_visits` (`id`, `ip`, `date`) VALUES (NULL, :clientIp, :date)';

        $params = [
            'clientIp' => $clientIp,
            'date' => $date
        ];

        $this->getEntityManager()->getConnection()->executeQuery($stmt, $params);
    }
}
