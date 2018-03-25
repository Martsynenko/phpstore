<?php

namespace App\Controller\Admin;

use App\Entity\PhpArticles;
use App\Entity\PhpArticlesVisits;
use App\Repository\PhpArticlesRepository;
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
     * @Route("/wde-master/admin/article/{id}", requirements={"id"="\d+"}, name="admin-article")
     * @param int $id
     * @param SessionInterface $session
     * @param AuthValidationHelper $authValidationHelper
     * @param PhpArticlesRepository $phpArticlesRepository
     * @return Response
     */
    public function index(
        $id = 0,
        SessionInterface $session,
        AuthValidationHelper $authValidationHelper,
        PhpArticlesRepository $phpArticlesRepository
    ) {
        $sessionKey = $session->get(LoginController::SESSION_SESSION_KEY);
        $userId = $session->get(LoginController::SESSION_USER_ID);

        if (!$authValidationHelper->checkAuthUser($userId, $sessionKey)) {
            return $this->redirectToRoute('admin-login');
        }

        /** @var PhpArticles $article */
        $article = $phpArticlesRepository->find($id);

        if ($article) {
            $textArticle = htmlspecialchars_decode($article->getText());
            $article->setText($textArticle);

            $dataArticle = ['article' => $article];

            return $this->render('admin/article/index.html.twig', $dataArticle);
        } elseif ($id == 0) {
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
            $entityManager->flush();
        }

        $articleId = $articleEntity->getId();

        return new JsonResponse($articleId);
    }

    /**
     * @Route("/wde-master/admin/article/delete", name="admin-articleDelete")
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteArticle(
        Request $request
    )
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dataArticle = $request->request->all();

        $articleId = $dataArticle['articleId'];

        $articleEntity = $entityManager->getRepository(PhpArticles::class)->find($articleId);

        $entityManager->remove($articleEntity);
        $entityManager->flush();

        return new JsonResponse(1);
    }
}
