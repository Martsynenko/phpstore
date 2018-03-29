<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 25.03.2018
 * Time: 17:14
 */

namespace App\Controller\PhpStore;

use App\Repository\PhpArticlesRepository;
use App\Services\CounterService\CounterService;
use App\Services\NavigationService\ArticleNavigationService;
use App\Services\PaginationService\ArticlePaginationService;
use App\TwigService\TwigService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class IndexController extends Controller
{
    const HOME_ARTICLES_LIMIT = 5;

    /**
     * @Route("/{page}", requirements={"page"="\d+"}, name="home")
     * @param int $page
     * @param Request $request
     * @param CounterService $counterService
     * @param ArticleNavigationService $articleNavigationService
     * @param ArticlePaginationService $articlePaginationService
     * @param PhpArticlesRepository $phpArticlesRepository
     * @return Response
     */
    public function index(
        $page = 1,
        Request $request,
        CounterService $counterService,
        ArticleNavigationService $articleNavigationService,
        ArticlePaginationService $articlePaginationService,
        PhpArticlesRepository $phpArticlesRepository
    )
    {
        $countArticles = $counterService->getCountArticles();

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
            TwigService::TWIG_KEY_COUNT_ARTICLES => $countArticles,
            'articles' => $articles,
            'pagination' => $pagination
        ]);
    }
}