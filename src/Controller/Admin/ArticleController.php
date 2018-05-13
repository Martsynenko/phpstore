<?php

namespace App\Controller\Admin;

use App\Entity\PhpArticles;
use App\Entity\PhpArticlesVisits;
use App\Entity\PhpUrls;
use App\Entity\PhpUrlsArticles;
use App\Repository\PhpArticlesRepository;
use App\Repository\PhpUrlsRepository;
use App\ValidationHelper\AuthValidationHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ArticleController extends Controller
{
    /**
     * @Route("/wde-master/admin/article/", name="admin-article")
     * @param Request $request
     * @param SessionInterface $session
     * @param AuthValidationHelper $authValidationHelper
     * @param PhpArticlesRepository $phpArticlesRepository
     * @return Response
     */
    public function index(
        Request $request,
        SessionInterface $session,
        AuthValidationHelper $authValidationHelper,
        PhpArticlesRepository $phpArticlesRepository
    ) {
        $articleId = $request->get('articleId');
        $sessionKey = $session->get(LoginController::SESSION_SESSION_KEY);
        $userId = $session->get(LoginController::SESSION_USER_ID);

        if (!$authValidationHelper->checkAuthUser($userId, $sessionKey)) {
            return $this->redirectToRoute('admin-login');
        }

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

    /**
     * @Route("/wde-master/admin/article/save", name="admin-articleSave")
     * @param Request $request
     * @return JsonResponse
     */
    public function saveArticle(
        Request $request
    )
    {
        $articleEntity = null;
        $articleVisitEntity = null;
        $urlEntity = null;
        $urlArticleEntity = null;

        $entityManager = $this->getDoctrine()->getManager();
        $dataArticle = $request->request->all();

        $articleId = $dataArticle['articleId'];

        if ($articleId) {
            /** @var PhpArticles $articleEntity */
            $articleEntity = $entityManager->getRepository(PhpArticles::class)->find($articleId);
            /** @var PhpArticlesVisits[] $articleVisitEntity */
            $articleVisitEntity = $entityManager->getRepository(
                PhpArticlesVisits::class)->findBy(['articleId' => $articleId]
            );
            $articleVisitEntity = current($articleVisitEntity);
            $urlArticle = $entityManager->getRepository(
                PhpUrlsArticles::class)->findBy(['articleId' => $articleId]
            );
            /** @var PhpUrlsArticles $urlArticleEntity */
            $urlArticleEntity = current($urlArticle);
            $urlId = $urlArticleEntity->getUrlId();
            /** @var PhpUrls $urlEntity */
            $urlEntity = $entityManager->getRepository(PhpUrls::class)->find($urlId);
            $urlEntity->setUrl($dataArticle['articleUrl']);
            $entityManager->persist($urlEntity);
        }

        if (!($articleEntity instanceof PhpArticles)) {
            $articleEntity = new PhpArticles();
        }

        $articleEntity->setSeoTitle($dataArticle['seoTitle']);
        $articleEntity->setSeoDescription($dataArticle['seoDescription']);
        $articleEntity->setSeoKeywords($dataArticle['seoKeywords']);
        $articleEntity->setTitle($dataArticle['titleArticle']);
        $articleEntity->setText(htmlspecialchars($dataArticle['textArticle']));
        $articleEntity->setTags($dataArticle['tagsArticle']);
        $articleEntity->setDate(date('Y-m-d'));
        $articleEntity->setStatus(strtolower($dataArticle['statusArticle']));

        $entityManager->persist($articleEntity);
        $entityManager->flush();

        if (!($articleVisitEntity instanceof PhpArticlesVisits)) {
            $articleVisitEntity = new PhpArticlesVisits();
            $articleVisitEntity->setArticleId($articleEntity->getId());
            $articleVisitEntity->setVisits(0);
            $entityManager->persist($articleVisitEntity);
        }

        if (!($urlEntity instanceof PhpUrls)) {
            $urlEntity = new PhpUrls();
            $urlEntity->setUrl($dataArticle['articleUrl']);
            $urlEntity->setSectionId(1);
            $entityManager->persist($urlEntity);
            $entityManager->flush();
        }

        if (!($urlArticleEntity instanceof PhpUrlsArticles)) {
            $urlArticleEntity = new PhpUrlsArticles();
            $urlArticleEntity->setArticleId($articleEntity->getId());
            $urlArticleEntity->setUrlId($urlEntity->getId());
            $entityManager->persist($urlArticleEntity);
        }

        $entityManager->flush();

        $articleId = $articleEntity->getId();

        return new JsonResponse($articleId);
    }

    /**
     * @Route("/wde-master/admin/article/delete", name="admin-articleDelete")
     * @param Request $request
     * @param PhpUrlsRepository $phpUrlsRepository
     * @return JsonResponse
     */
    public function deleteArticle(
        Request $request,
        PhpUrlsRepository $phpUrlsRepository
    )
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dataArticle = $request->request->all();

        $articleId = $dataArticle['articleId'];
        $phpUrlsRepository->deleteUrlByArticleId($articleId);

        $articleEntity = $entityManager->getRepository(PhpArticles::class)->find($articleId);

        $entityManager->remove($articleEntity);
        $entityManager->flush();

        return new JsonResponse(1);
    }
}
