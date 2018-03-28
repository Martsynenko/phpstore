<?php

namespace App\Repository;

use App\Entity\PhpArticles;
use App\Entity\PhpArticlesVisits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PhpArticlesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpArticles::class);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getArticlesIDTitleByPage($offset = 0, $limit)
    {
        return $this->createQueryBuilder('pa')
            ->select('pa.id, pa.title, pa.date, pav.visits, pa.status')
            ->leftJoin(PhpArticlesVisits::class, 'pav', Join::WITH, 'pav.articleId = pa.id')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();
    }

    public function getCountArticles()
    {
        return $this->createQueryBuilder('pa')
            ->select('count(pa.id) as countArticles')
            ->getQuery()
            ->getArrayResult();
    }

    public function getArticles($limit, $offset = 0)
    {
        return $this->createQueryBuilder('pa')
            ->select()
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();


    }
}
