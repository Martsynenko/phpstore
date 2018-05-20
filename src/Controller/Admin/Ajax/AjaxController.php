<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 19.05.2018
 * Time: 11:09
 */

namespace App\Controller\Admin\Ajax;

use App\Controller\Admin\AbstractController;
use App\Entity\PhpArticles;
use App\Entity\PhpArticlesVisits;
use App\Entity\PhpUrls;
use App\Entity\PhpUrlsArticles;
use App\Repository\PhpUrlsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AjaxController extends AbstractController
{
    const SECTION_HOME = 'Home';
    const SECTION_ARTICLE = 'Article';

    const SECTION_HOME_ID = 1;
    const SECTION_ARTICLE_ID = 2;

    /**
     * @Route("/wde-master/admin/action/article/save/", name="admin-article-save")
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
            $urlEntity->setSectionId(self::SECTION_ARTICLE_ID);
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
     * @Route("/wde-master/admin/action/article/delete/", name="admin-article-delete")
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