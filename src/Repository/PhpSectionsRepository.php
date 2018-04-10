<?php

namespace App\Repository;

use App\Entity\PhpSections;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpSections|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpSections|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpSections[]    findAll()
 * @method PhpSections[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpSectionsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpSections::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.something = :value')->setParameter('value', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
