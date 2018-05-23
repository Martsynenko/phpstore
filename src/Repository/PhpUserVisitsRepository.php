<?php

namespace App\Repository;

use App\Entity\PhpUserVisits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PhpUserVisitsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpUserVisits::class);
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
     * @param $articleId
     * @return bool
     */
    public function checkUserVisit($clientIp, $date, $articleId)
    {
        $stmt = 'SELECT `id` FROM `php_user_visits` uv 
                 WHERE uv.ip = :clientIp 
                 AND uv.date = :date
                 AND uv.article_id = :articleId';

        $params = [
            'clientIp' => $clientIp,
            'date' => $date,
            'articleId' => $articleId
        ];

        $id = $this->getEntityManager()->getConnection()->executeQuery($stmt, $params)->fetchColumn();

        if ($id) {
            return $id;
        }

        return false;
    }

    /**
     * @param $clientIp
     * @param $date
     * @param $articleId
     */
    public function insertUserVisit($clientIp, $date, $articleId)
    {
        $stmt = 'INSERT INTO `php_user_visits` (`id`, `ip`, `date`, `article_id`)
                 VALUES (NULL, :clientIp, :date, :articleId)';

        $params = [
            'clientIp' => $clientIp,
            'date' => $date,
            'articleId' => $articleId
        ];

        $this->getEntityManager()->getConnection()->executeQuery($stmt, $params);
    }

    public function getLastUserVisitId()
    {
        $stmt = 'SELECT MAC(`id`) FROM `user_visits`';

        return $this->getEntityManager()->getConnection()->executeQuery($stmt);
    }
}
