<?php

namespace App\Controller\Admin;

use App\Repository\PhpArticlesRepository;
use App\Services\NavigationService\ArticleNavigationService;
use App\ValidationHelper\AuthValidationHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ArticlesController extends Controller
{
    const ADMIN_LIMIT_ARTICLES = 30;
    /**
     * @Route("wde-master/admin/articles/{page}", name="admin-articles")
     * @param int $page
     * @param SessionInterface $session
     * @param AuthValidationHelper $authValidationHelper
     * @param ArticleNavigationService $articleNavigationService
     * @param PhpArticlesRepository $phpArticlesRepository
     * @return Response
     */
    public function index(
        $page = 1,
        SessionInterface $session,
        AuthValidationHelper $authValidationHelper,
        ArticleNavigationService $articleNavigationService,
        PhpArticlesRepository $phpArticlesRepository
    ) {
        $sessionKey = $session->get(LoginController::SESSION_SESSION_KEY);
        $userId = $session->get(LoginController::SESSION_USER_ID);

        if (!$authValidationHelper->checkAuthUser($userId, $sessionKey)) {
            return $this->redirectToRoute('login');
        }
        $offset = $articleNavigationService->getOffset($page, self::ADMIN_LIMIT_ARTICLES);
        $articles = $phpArticlesRepository->getArticlesIDTitleByPage($offset, self::ADMIN_LIMIT_ARTICLES);
        return $this->render('admin/articles/index.html.twig', [
            'articles' => $articles
        ]);
    }
}
