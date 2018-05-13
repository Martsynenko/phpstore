<?php

namespace App\Repository;

use App\Entity\PhpArticlesVisits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PhpArticlesVisitsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpArticlesVisits::class);
    }

    /**
     * @param int $articleId
     */
    public function updateVisitArticleByArticleId($articleId)
    {
        $stmt = 'UPDATE `php_articles_visits` SET `visits` = `visits` + 1
                 WHERE `article_id` = :articleId';

        $params = [
            'articleId' => $articleId
        ];

        $this->_em->getConnection()->executeQuery($stmt, $params);
    }
}
