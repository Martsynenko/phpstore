<?php

namespace App\Repository;

use App\Entity\PhpUrlsArticles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpUrlsArticles|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpUrlsArticles|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpUrlsArticles[]    findAll()
 * @method PhpUrlsArticles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpUrlsArticlesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpUrlsArticles::class);
    }

    /**
     * @param int $urlId
     * @return array|null
     */
    public function getArticleIdByUrlId($urlId)
    {
        return $this->createQueryBuilder('pua')
            ->select('pua.articleId')
            ->where('pua.urlId = :urlId')
            ->setParameter('urlId', $urlId)
            ->getQuery()
            ->getResult();
    }
}
