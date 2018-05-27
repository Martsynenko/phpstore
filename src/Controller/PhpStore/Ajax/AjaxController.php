<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 24.04.2018
 * Time: 21:07
 */

namespace App\Controller\PhpStore\Ajax;

use App\Entity\PhpCommentsUsers;
use App\Formatter\DateFormatter;
use App\Repository\PhpArticlesCommentsRepository;
use App\Repository\PhpCommentsUsersRepository;
use App\Repository\PhpUsersVerificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AjaxController extends Controller
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';
    const STATUS_VERIFICATION = 'verification';

    const KEY_URL = 'url';
    const KEY_USER_NAME = 'userName';
    const KEY_USER_EMAIL = 'userEmail';
    const KEY_COMMENT_TEXT = 'commentText';
    const KEY_ARTICLE_ID = 'articleId';
    const KEY_COUNT_COMMENTS = 'countComments';

    /**
     * @Route("/action/article/save-comment/", name="article-save-comment")
     * @param Request $request
     * @param DateFormatter $dateFormatter
     * @param PhpArticlesCommentsRepository $phpArticlesCommentsRepository
     * @param PhpCommentsUsersRepository $phpCommentsUsersRepository
     * @param PhpUsersVerificationRepository $phpUsersVerificationRepository
     * @param \Swift_Mailer $mailer
     * @return JsonResponse
     */
    public function saveArticleComment(
        Request $request,
        DateFormatter $dateFormatter,
        PhpArticlesCommentsRepository $phpArticlesCommentsRepository,
        PhpCommentsUsersRepository $phpCommentsUsersRepository,
        PhpUsersVerificationRepository $phpUsersVerificationRepository,
        \Swift_Mailer $mailer
    )
    {
        $status = self::STATUS_SUCCESS;
        $responseMessage = false;
        $dataComment = $request->request->all();
        $articleId = $dataComment[PhpArticlesCommentsRepository::ENTITY_ARTICLE_ID];
        $userName = htmlspecialchars(strip_tags($dataComment[self::KEY_USER_NAME]));
        $userEmail = htmlspecialchars(strip_tags($dataComment[self::KEY_USER_EMAIL]));
        $comment = htmlspecialchars(strip_tags($dataComment[self::KEY_COMMENT_TEXT]));
        $date = date('Y-m-d H:i:s');

        $commentUsers = $phpCommentsUsersRepository->findBy(['email' => $userEmail]);
        /** @var PhpCommentsUsers $commentUser */
        $commentUser = array_shift($commentUsers);
        if ($commentUser instanceof PhpCommentsUsers) {
            if ($commentUser->getName() === $userName) {
                if ($commentUser->getVerificationStatus() === PhpCommentsUsersRepository::VERIFICATION_STATUS_YES) {
                    $responseMessage = 'Ваш комментарий опубликован!';
                    $userId = $commentUser->getId();
                    $phpArticlesCommentsRepository->insertArticleCommentByUserId($userId, $comment, $articleId, $date);
                    $date = $dateFormatter->formatDateTimeForComments(false, $date);
                } else {
                    $status = self::STATUS_ERROR;
                    $responseMessage = 'К сожалению Ваш почтовый адрес еще не подтвержден или заблокирован! Если Вы еще не подтвердили Ваш почтовый адрес перейдите по ссылке в письме, которые было отправлено на Ваш email!';
                }
            } else {
                $status = self::STATUS_ERROR;
                $responseMessage = 'Вы указали неверное имя! За данным почтовым адресом зарегестрировано другое имя!';
            }
        } else {
            $status = self::STATUS_VERIFICATION;
            $phpArticlesCommentsRepository->insertArticleComment($userName, $userEmail, $comment, $articleId);
            $userId = $phpCommentsUsersRepository->getLastInsertUser();
            $url = $dataComment[self::KEY_URL];
            $hashId = md5(uniqid(rand(),true));
            $hash = md5($articleId . $userName . $userEmail . $hashId);

            $phpUsersVerificationRepository->insertUserVerificationData($userId, $url, $hash);

            $linkVerification = 'https://phpstore.com.ua/verification/?hash=' . $hash;
            $message = (new \Swift_Message('Подтверждение почтового адреса'))
                ->setFrom('phpstore.mailer@gmail.com', 'PhpStore')
                ->setTo($userEmail)
                ->setBody(
                    $this->renderView(
                        'phpstore/emails/registration.html.twig',
                        [
                            'userName' => $userName,
                            'linkVerification' => $linkVerification
                        ]
                    ),
                    'text/html'
                );

            $mailer->send($message);
        }


        return new JsonResponse([
            'status' => $status,
            'message' => $responseMessage,
            'data' => [
                'name' => $userName,
                'date' => $date,
                'comment' => $comment
            ]
        ]);
    }

    /**
     * @Route("/action/article/load-comments/")
     * @param Request $request
     * @param DateFormatter $dateFormatter
     * @param PhpArticlesCommentsRepository $phpArticlesCommentsRepository
     * @return JsonResponse
     */
    public function loadComments(
        Request $request,
        DateFormatter $dateFormatter,
        PhpArticlesCommentsRepository $phpArticlesCommentsRepository
    )
    {
        $showBtnLoadComments = false;
        $data = $request->request->all();
        $articleId = (int) $data[self::KEY_ARTICLE_ID];
        $offset = (int) $data[self::KEY_COUNT_COMMENTS];

        $nextCount = $offset + 5;
        $countAllComments = $phpArticlesCommentsRepository->getCountCommentsByArticleId($articleId);
        $resultCount = $countAllComments - $nextCount;
        if ($resultCount > 0) {
            $showBtnLoadComments = true;
        }

        $comments = $phpArticlesCommentsRepository->getCommentsByArticleId($articleId, $offset);
        $comments = $dateFormatter->formatDateTimeForComments($comments);
        return new JsonResponse([
            'status' => 'success',
            'comments' => $comments,
            'showBtnLoadComments' => $showBtnLoadComments
        ]);
    }
}

