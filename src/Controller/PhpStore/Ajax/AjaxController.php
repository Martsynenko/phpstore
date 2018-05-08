<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 24.04.2018
 * Time: 21:07
 */

namespace App\Controller\PhpStore\Ajax;

use App\Entity\PhpCommentsUsers;
use App\Repository\PhpArticlesCommentsRepository;
use App\Repository\PhpCommentsUsersRepository;
use App\Repository\PhpUsersVerificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AjaxController extends Controller
{
    const KEY_URL = 'url';
    const KEY_USER_NAME = 'userName';
    const KEY_USER_EMAIL = 'userEmail';
    const KEY_COMMENT_TEXT = 'commentText';

    /**
     * @Route("/action/article/save-comment/", name="article-save-comment")
     * @param Request $request
     * @param PhpArticlesCommentsRepository $phpArticlesCommentsRepository
     * @param PhpCommentsUsersRepository $phpCommentsUsersRepository
     * @param PhpUsersVerificationRepository $phpUsersVerificationRepository
     * @param \Swift_Mailer $mailer
     * @return JsonResponse
     */
    public function saveArticleComment(
        Request $request,
        PhpArticlesCommentsRepository $phpArticlesCommentsRepository,
        PhpCommentsUsersRepository $phpCommentsUsersRepository,
        PhpUsersVerificationRepository $phpUsersVerificationRepository,
        \Swift_Mailer $mailer
    )
    {
        $dataComment = $request->request->all();
        $articleId = $dataComment[PhpArticlesCommentsRepository::ENTITY_ARTICLE_ID];
        $userName = $dataComment[self::KEY_USER_NAME];
        $userEmail = $dataComment[self::KEY_USER_EMAIL];
        $comment = $dataComment[self::KEY_COMMENT_TEXT];

        $commentUsers = $phpCommentsUsersRepository->findBy(['email' => $userEmail]);
        /** @var PhpCommentsUsers $commentUser */
        $commentUser = array_shift($commentUsers);
        if ($commentUser instanceof PhpCommentsUsers) {
            $userId = $commentUser->getId();
            $phpArticlesCommentsRepository->insertArticleCommentByUserId($userId, $comment, $articleId);
        } else {
            $phpArticlesCommentsRepository->insertArticleComment($userName, $userEmail, $comment, $articleId);
            $userId = $phpCommentsUsersRepository->getLastInsertUser();
            $url = $dataComment[self::KEY_URL];
            $hashId = md5(uniqid(rand(),true));
            $hash = md5($articleId . $userName . $userEmail . $hashId);

            $phpUsersVerificationRepository->insertUserVerificationData($userId, $url, $hash);

            $linkVerification = 'http://phpstore.ua/verification/?hash=' . $hash;
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


        return new JsonResponse(['status' => 'success', 'message' => '']);
    }
}

