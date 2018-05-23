<?php

namespace App\Repository;

use App\Entity\PhpArticles;
use App\Entity\PhpUrlsArticles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpUrlsArticles|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpUrlsArticles|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpUrlsArticles[]    findAll()
 * @method PhpUrlsArticles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpUrlsArticlesRepository extends ServiceEntityRepository
{
    const ENTITY_ARTICLE_ID = 'articleId';

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
            ->select('pua.articleId, pa.status')
            ->join(PhpArticles::class, 'pa', Join::WITH, 'pa.id = pua.articleId')
            ->where('pua.urlId = :urlId')
            ->setParameter('urlId', $urlId)
            ->getQuery()
            ->getResult();
    }
}
