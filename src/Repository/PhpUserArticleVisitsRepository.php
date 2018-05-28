<?php

namespace App\Repository;

use App\Entity\PhpUserArticleVisits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpUserArticleVisits|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpUserArticleVisits|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpUserArticleVisits[]    findAll()
 * @method PhpUserArticleVisits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpUserArticleVisitsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpUserArticleVisits::class);
    }

    /**
     * @param string $ip
     * @param int $articleId
     * @return bool|string
     */
    public function getIdUserIpArticleId($ip, $articleId)
    {
        $stmt = 'SELECT `id` FROM php_user_article_visits puav
                 WHERE puav.ip = :ip AND puav.article_id = :articleId';

        $params = [
            'ip' => $ip,
            'articleId' => $articleId
        ];

        $id = $this->_em->getConnection()->executeQuery($stmt, $params)->fetchColumn();

        return $id;
    }

    /**
     * @param string $ip
     * @param int $articleId
     */
    public function setUserVisit($ip, $articleId)
    {
        $stmt = 'INSERT INTO `php_user_article_visits` (`id`, `ip`, `article_id`)
                 VALUES (NULL, :ip, :articleId)';

        $params = [
            'ip' => $ip,
            'articleId' => $articleId
        ];

        $this->_em->getConnection()->executeQuery($stmt, $params);
    }
}
