<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 08.05.2018
 * Time: 21:24
 */

namespace App\Services\VisitService;

use App\Repository\PhpUserVisitsRepository;
use App\Services\VisitService\VisitTypes\ArticleVisitType;
use App\Services\VisitService\VisitTypes\VisitTypeInterface;
use App\Services\UrlDataService\UrlData;

class VisitService
{
    /** @var  VisitTypeInterface[] $typeServices */
    private $typeServices;

    /** @var  PhpUserVisitsRepository $userVisitsRepository */
    private $userVisitsRepository;

    public function __construct(
        PhpUserVisitsRepository $userVisitsRepository,
        ArticleVisitType $articleType
    )
    {
        $this->userVisitsRepository = $userVisitsRepository;

        $this->typeServices = [
            $articleType
        ];
    }

    /**
     * @param UrlData $urlData
     */
    public function updateVisit(UrlData $urlData)
    {
        $isVisited = $this->updateSiteVisit($urlData->getRequest()->getClientIp(), $urlData->getArticleId());

        if (!$isVisited) {
            $typeService = $this->defineTypeService($urlData->getSection());
            if ($typeService instanceof VisitTypeInterface) {
                $typeService->updateVisit($urlData);
            }
        }
    }

    /**
     * @param string $clientIp
     * @param $articleId
     * @return bool
     */
    private function updateSiteVisit($clientIp, $articleId)
    {
        $articleId = isset($articleId) ? $articleId : 0;

        $date = date('Y-m-d');

        if ($this->userVisitsRepository->checkUserVisit($clientIp, $date, $articleId)) {
            return true;
        }

        $this->userVisitsRepository->insertUserVisit($clientIp, $date, $articleId);

        return false;
    }

    /**
     * @param $section
     * @return VisitTypeInterface|null
     */
    private function defineTypeService($section)
    {
        /** @var VisitTypeInterface $typeService */
        foreach ($this->typeServices as $typeService) {
            if ($typeService->defineTypeServiceBySection($section)) {
                return $typeService;
            }
        }

        return null;
    }
}