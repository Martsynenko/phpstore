<?php

namespace App\Repository;

use App\Entity\PhpArticlesComments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhpArticlesComments|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhpArticlesComments|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhpArticlesComments[]    findAll()
 * @method PhpArticlesComments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhpArticlesCommentsRepository extends ServiceEntityRepository
{
    const ENTITY_ARTICLE_ID = 'articleId';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhpArticlesComments::class);
    }

    /**
     * @param string $userName
     * @param string $userEmail
     * @param string $comment
     * @param int $articleId
     */
    public function insertArticleComment(
        $userName,
        $userEmail,
        $comment,
        $articleId
    ) {
        $date = date('Y-m-d H:i:s');

        $stmt = 'BEGIN;
            INSERT INTO `php_comments_users` (`id`, `name`, `email`) VALUES (NULL, :userName, :userEmail);
            INSERT INTO `php_articles_comments` (`id`, `comment`, `date`, `article_id`, `comment_user_id`)
            VALUES (NULL, :comment, :date, :articleId, LAST_INSERT_ID());
        COMMIT;';

        $params = [
            'userName' => $userName,
            'userEmail' => $userEmail,
            'comment' => $comment,
            'date' => $date,
            'articleId' => $articleId
        ];

        $this->_em->getConnection()->executeQuery($stmt, $params);
    }

    /**
     * @param int $userId
     * @param string $comment
     * @param int $articleId
     */
    public function insertArticleCommentByUserId($userId, $comment, $articleId)
    {
        $date = date('Y-m-d H:i:s');

        $stmt = 'INSERT INTO `php_articles_comments` (`id`, `comment`, `date`, `article_id`, `comment_user_id`)
            VALUES (NULL, :comment, :date, :articleId, :userId);';

        $params = [
            'userId' => $userId,
            'comment' => $comment,
            'date' => $date,
            'articleId' => $articleId
        ];

        $this->_em->getConnection()->executeQuery($stmt, $params);
    }

    public function getCommentsByArticleId($articleId)
    {

    }

}
