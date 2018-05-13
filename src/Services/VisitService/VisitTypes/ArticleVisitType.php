<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 08.05.2018
 * Time: 21:25
 */

namespace App\Services\VisitService\VisitTypes;

use App\Repository\PhpArticlesVisitsRepository;
use App\Services\UrlDataService\UrlData;

class ArticleVisitType implements VisitTypeInterface
{
    const TYPE_SECTION_ARTICLE = 'Article';

    /** @var  PhpArticlesVisitsRepository $phpArticlesVisitsRepository */
    private $phpArticlesVisitsRepository;

    public function __construct(
        PhpArticlesVisitsRepository $phpArticlesVisitsRepository
    ) {
        $this->phpArticlesVisitsRepository = $phpArticlesVisitsRepository;
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
        $this->phpArticlesVisitsRepository->updateVisitArticleByArticleId($urlData->getUrlId());
    }
}