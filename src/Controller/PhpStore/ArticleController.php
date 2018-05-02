<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 27.03.2018
 * Time: 22:54
 */

namespace App\Controller\PhpStore;

use App\Entity\PhpArticles;
use App\Repository\PhpArticlesRepository;
use App\Services\UrlDataService\UrlDataServices\ArticleUrlData;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ArticleController extends Controller
{
    /**
     * @Route("{articleUrl}", requirements={"articleUrl"="article/.+/"}, name="article")
     * @param ArticleUrlData $urlData
     * @param PhpArticlesRepository $phpArticlesRepository
     * @return Response
     */
    public function index(
        ArticleUrlData $urlData,
        PhpArticlesRepository $phpArticlesRepository
    )
    {
        $articleId = $urlData->getArticleId();
        if ($articleId) {
            $article = $phpArticlesRepository->getFullArticleDataByArticleId($articleId);
            $article = array_shift($article);
            $textArticle = htmlspecialchars_decode($article[PhpArticlesRepository::COLUMN_TEXT]);
            $article[PhpArticlesRepository::COLUMN_TEXT] = $textArticle;
        }
        return $this->render('phpstore/article/article.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/article/load-comments/")
     * @return JsonResponse
     */
    public function loadComments()
    {
        return new JsonResponse(['test']);
    }
}

