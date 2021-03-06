<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 25.03.2018
 * Time: 17:14
 */

namespace App\Controller\PhpStore;

use App\Services\VisitService\VisitService;
use App\Repository\PhpArticlesRepository;
use App\Services\CounterService\CounterService;
use App\Services\NavigationService\ArticleNavigationService;
use App\Services\PaginationService\ArticlePaginationService;
use App\Services\TwigService\TwigService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class IndexController extends AbstractController
{
    const HOME_ARTICLES_LIMIT = 5;

    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param CounterService $counterService
     * @param ArticleNavigationService $articleNavigationService
     * @param ArticlePaginationService $articlePaginationService
     * @param PhpArticlesRepository $phpArticlesRepository
     * @return Response
     */
    public function index(
        Request $request,
        CounterService $counterService,
        ArticleNavigationService $articleNavigationService,
        ArticlePaginationService $articlePaginationService,
        PhpArticlesRepository $phpArticlesRepository
    )
    {
        $page = $request->get('page');
        if (!$page) {
            $page = 1;
        }
        $countBreadcrumbArticles = $counterService->getBreadcrumbCountArticles();

        $routeName = $request->get('_route');

        $offset = $articleNavigationService->getOffset($page, self::HOME_ARTICLES_LIMIT);

        $articles = $phpArticlesRepository->getShortDataArticles(self::HOME_ARTICLES_LIMIT, $offset);

        $pagination = $articlePaginationService->getArticlePagination($page, $routeName);

        foreach ($articles as &$article) {
            $article['text'] = substr(
                strip_tags(htmlspecialchars_decode($article['text'])), 0, -2
                ) . '...';
        }

        return $this->render('phpstore/base.html.twig', [
            TwigService::TWIG_KEY_COUNT_ARTICLES => $countBreadcrumbArticles,
            TwigService::TWIG_KEY_ARTICLES => $articles,
            TwigService::TWIG_KEY_PAGINATION => $pagination
        ]);
    }
}