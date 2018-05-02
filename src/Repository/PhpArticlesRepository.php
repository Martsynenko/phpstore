<?php

namespace App\Repository;

use App\Entity\PhpArticles;
use App\Entity\PhpArticlesVisits;
use App\Entity\PhpUrls;
use App\Entity\PhpUrlsArticles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PhpArticlesRepository extends ServiceEntityRepository
{
    const COLUMN_TEXT = 'text';

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
            ->select('pa.id, pa.title, pa.date, pav.visits, pa.status, pu.url')
            ->leftJoin(
                PhpArticlesVisits::class,
                'pav',
                Join::WITH,
                'pav.articleId = pa.id'
            )
            ->leftJoin(
                PhpUrlsArticles::class,
                'pua',
                Join::WITH,
                'pua.articleId = pa.id'
            )
            ->leftJoin(PhpUrls::class, 'pu', Join::WITH, 'pu.id = pua.urlId')
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

    public function getShortDataArticles($limit, $offset = 0)
    {
        return $this->createQueryBuilder('pa')
            ->select('pa.id, pa.title, substring(pa.text, 1, 300) as text, pa.date, pav.visits, pu.url')
            ->join(PhpUrlsArticles::class, 'pua', Join::WITH, 'pua.articleId = pa.id')
            ->join(PhpUrls::class, 'pu', Join::WITH, 'pu.id = pua.urlId')
            ->join(
                PhpArticlesVisits::class,
                'pav',
                Join::WITH,
                'pa.id = pav.articleId'
            )
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();
    }

    public function getFullArticleDataByArticleId($articleId)
    {
        $stmt = "SELECT `pa`.*, `pav`.`visits` FROM `php_articles` AS `pa`
                    JOIN `php_articles_visits` AS `pav` ON `pa`.`id` = `pav`.`article_id`
                 WHERE `pa`.`id` = :articleId";

        $params = [
            'articleId' => $articleId
        ];

        return $this->_em->getConnection()->executeQuery($stmt, $params)->fetchAll();
    }
}
