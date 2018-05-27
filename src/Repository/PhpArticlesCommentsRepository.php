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
     * @param $date
     */
    public function insertArticleCommentByUserId($userId, $comment, $articleId, $date)
    {
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

    public function getCommentsByArticleId($articleId, $offset = 0)
    {
        $stmt = 'SELECT pcu.name, pac.date, pac.comment FROM php_articles_comments pac
                  JOIN php_comments_users pcu ON pac.comment_user_id = pcu.id
                  WHERE pac.article_id = :articleId AND pcu.verification_status = :status
                 ORDER BY pac.date DESC
                 LIMIT :offset, 5';

        $params = [
            'articleId' => $articleId,
            'status' => PhpCommentsUsersRepository::VERIFICATION_STATUS_YES,
            'offset' => $offset
        ];

        $types = [
            'articleId' => \PDO::PARAM_INT,
            'status' => \PDO::PARAM_STR,
            'offset' => \PDO::PARAM_INT
        ];

        return $this->_em->getConnection()->executeQuery($stmt, $params, $types)->fetchAll();
    }

    public function getCountCommentsByArticleId($articleId)
    {
        $stmt = 'SELECT COUNT(pac.`id`) as `count` FROM `php_articles_comments` pac
                    JOIN php_comments_users pcu ON pac.comment_user_id = pcu.id
                    AND pcu.verification_status = :status
                 WHERE pac.article_id = :articleId';

        $params = [
            'articleId' => $articleId,
            'status' => PhpCommentsUsersRepository::VERIFICATION_STATUS_YES
        ];

        return $this->_em->getConnection()->executeQuery($stmt, $params)->fetchColumn();
    }

}
