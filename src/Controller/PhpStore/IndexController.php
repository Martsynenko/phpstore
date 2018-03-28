<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 25.03.2018
 * Time: 17:14
 */

namespace App\Controller\PhpStore;

use App\CounterService\CounterService;
use App\Repository\PhpArticlesRepository;
use App\TwigService\TwigService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class IndexController extends Controller
{
    const ARTICLES_LIMIT = 5;

    /**
     * @Route("/", name="home")
     * @param CounterService $counterService
     * @param PhpArticlesRepository $phpArticlesRepository
     * @return Response
     */
    public function index(
        CounterService $counterService,
        PhpArticlesRepository $phpArticlesRepository
    )
    {
        $countArticles = $counterService->getCountArticles();

        $articles = $phpArticlesRepository->getArticles(self::ARTICLES_LIMIT);

        return $this->render('phpstore/base.html.twig', [
            TwigService::TWIG_KEY_COUNT_ARTICLES => $countArticles,
            $articles
        ]);
    }
}