<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 27.03.2018
 * Time: 22:54
 */

namespace App\Controller\PhpStore;

use App\Entity\PhpArticles;
use App\Formatter\DateFormatter;
use App\Repository\PhpArticlesCommentsRepository;
use App\Repository\PhpArticlesRepository;
use App\Services\CounterService\CounterService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("{articleUrl}", requirements={"articleUrl"="article/.+/"}, name="article")
     * @param DateFormatter $dateFormatter
     * @param CounterService $counterService
     * @param PhpArticlesRepository $phpArticlesRepository
     * @param PhpArticlesCommentsRepository $phpArticlesCommentsRepository
     * @return Response
     */
    public function index(
        DateFormatter $dateFormatter,
        CounterService $counterService,
        PhpArticlesRepository $phpArticlesRepository,
        PhpArticlesCommentsRepository $phpArticlesCommentsRepository
    )
    {
        $showBtnLoadComments = false;
        $articleId = $this->urlData->getArticleId();
        $status = $this->urlData->getStatus();
        if ($articleId && $status === PhpArticles::STATUS_PUBLISHED) {
            $article = $phpArticlesRepository->getFullArticleDataByArticleId($articleId);
            $article = array_shift($article);
            $textArticle = htmlspecialchars_decode($article[PhpArticlesRepository::COLUMN_TEXT]);
            $textArticle = htmlspecialchars_decode($textArticle);
            $article[PhpArticlesRepository::COLUMN_TEXT] = $textArticle;
            $article['url'] = $this->urlData->getUrl();

            $comments = $phpArticlesCommentsRepository->getCommentsByArticleId($articleId);
            $comments = $dateFormatter->formatDateTimeForComments($comments);
            $countComments = $counterService->getCountArticleComments($articleId);
            if ($countComments > 5) {
                $showBtnLoadComments = true;
            }

            return $this->render('phpstore/article/article.html.twig', [
                'article' => $article,
                'comments' => $comments,
                'countComments' => $countComments,
                'showBtnLoadComments' => $showBtnLoadComments
            ]);
        } else {
            return $this->render('phpstore/404.html.twig');
        }
    }
}

