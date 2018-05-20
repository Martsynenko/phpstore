<?php

namespace App\Controller\Admin;

use App\Entity\PhpArticles;
use App\Entity\PhpArticlesVisits;
use App\Entity\PhpUrls;
use App\Entity\PhpUrlsArticles;
use App\Repository\PhpArticlesRepository;
use App\Repository\PhpUrlsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController
{
    /**
     * @Route("/wde-master/admin/article/", name="admin-article")
     * @param Request $request
     * @param PhpArticlesRepository $phpArticlesRepository
     * @return Response
     */
    public function index(
        Request $request,
        PhpArticlesRepository $phpArticlesRepository
    ) {
        $articleId = $request->get('articleId');

        if ($articleId) {
            $article = $phpArticlesRepository->getAdminFullArticleDataByArticleId($articleId);
            if ($article) {
                $article = array_shift($article);
                $textArticle = htmlspecialchars_decode($article['text']);
                $article['text'] = $textArticle;
            }

            return $this->render('admin/article/index.html.twig', ['article' => $article]);
        } elseif ($articleId == 0) {
            return $this->render('admin/article/index.html.twig', []);
        }

        return $this->render('admin/article/wrongArticle.html.twig');
    }
}
