<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 08.05.2018
 * Time: 21:25
 */

namespace App\Services\VisitService\VisitTypes;

use App\Repository\PhpArticlesVisitsRepository;
use App\Repository\PhpUserArticleVisitsRepository;
use App\Services\UrlDataService\UrlData;

class ArticleVisitType implements VisitTypeInterface
{
    const TYPE_SECTION_ARTICLE = 'Article';

    /** @var  PhpArticlesVisitsRepository $phpArticlesVisitsRepository */
    private $phpArticlesVisitsRepository;

    /** @var  PhpUserArticleVisitsRepository $phpUserArticleVisitsRepository */
    private $phpUserArticleVisitsRepository;

    public function __construct(
        PhpArticlesVisitsRepository $phpArticlesVisitsRepository,
        PhpUserArticleVisitsRepository $phpUserArticleVisitsRepository
    ) {
        $this->phpArticlesVisitsRepository = $phpArticlesVisitsRepository;
        $this->phpUserArticleVisitsRepository = $phpUserArticleVisitsRepository;
    }

    /**
     * @param $section
     * @return bool
     */
    public function defineTypeServiceBySection($section)
    {
        if ($section == self::TYPE_SECTION_ARTICLE) {
            return true;
        }

        return false;
    }

    /**
     * @param UrlData $urlData
     */
    public function updateVisit(UrlData $urlData)
    {
        $ip = $urlData->getRequest()->getClientIp();
        $articleId = $urlData->getArticleId();

        if (!$this->checkArticleUserVisit($ip, $articleId)) {
            $this->phpArticlesVisitsRepository->updateVisitArticleByArticleId($urlData->getArticleId());
        }
    }

    /**
     * @param string $ip
     * @param int $articleId
     * @return bool
     */
    private function checkArticleUserVisit($ip, $articleId)
    {
        $id = $this->phpUserArticleVisitsRepository->getIdUserIpArticleId($ip, $articleId);

        if ($id) {
            return true;
        }

        $this->phpUserArticleVisitsRepository->setUserVisit($ip, $articleId);

        return false;
    }
}