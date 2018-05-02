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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AjaxController extends Controller
{
    const KEY_USER_NAME = 'userName';
    const KEY_USER_EMAIL = 'userEmail';
    const KEY_COMMENT_TEXT = 'commentText';

    /**
     * @Route("/action/article/save-comment/", name="article-save-comment")
     * @param Request $request
     * @param PhpArticlesCommentsRepository $phpArticlesCommentsRepository
     * @param PhpCommentsUsersRepository $phpCommentsUsersRepository
     * @return JsonResponse
     */
    public function saveArticleComment(
        Request $request,
        PhpArticlesCommentsRepository $phpArticlesCommentsRepository,
        PhpCommentsUsersRepository $phpCommentsUsersRepository
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
        }


        return new JsonResponse([1]);
    }
}

