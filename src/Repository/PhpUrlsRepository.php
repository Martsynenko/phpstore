<?php

namespace App\Repository;

use App\Entity\PhpSections;
use App\Entity\PhpUrls;
use App\Entity\PhpUrlsArticles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpUrls|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpUrls|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpUrls[]    findAll()
 * @method PhpUrls[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpUrlsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpUrls::class);
    }

    /**
     * @param string $url
     * @return mixed
     */
    public function getUrlDataByUrl($url)
    {
        return $this->createQueryBuilder('pu')
            ->select('pu.id, pu.url, pu.sectionId, ps.section')
            ->join(PhpSections::class, 'ps', Join::WITH, 'pu.sectionId = ps.id')
            ->where('pu.url = :url')
            ->setParameter('url', $url)
            ->getQuery()
            ->getArrayResult();
    }

    public function deleteUrlByArticleId($articleId)
    {
        $sql = 'DELETE `php_urls` FROM `php_urls`
                  JOIN `php_urls_articles` ON `php_urls`.`id` = `php_urls_articles`.`url_id`
                WHERE `php_urls_articles`.`article_id` = :articleId';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute(['articleId' => $articleId]);
    }
}
