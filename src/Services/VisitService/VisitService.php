<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 08.05.2018
 * Time: 21:24
 */

namespace App\Services\VisitService;

use App\Repository\UserVisitsRepository;
use App\Services\VisitService\VisitTypes\ArticleVisitType;
use App\Services\VisitService\VisitTypes\VisitTypeInterface;
use App\Services\UrlDataService\UrlData;

class VisitService
{
    /** @var  VisitTypeInterface[] $typeServices */
    private $typeServices;

    /** @var  UserVisitsRepository $userVisitsRepository */
    private $userVisitsRepository;

    public function __construct(
        UserVisitsRepository $userVisitsRepository,
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
        $this->updateSiteVisit($urlData->getRequest()->getClientIp());

        $typeService = $this->defineTypeService($urlData->getSection());
        if ($typeService instanceof VisitTypeInterface) {
            $typeService->updateVisit($urlData);
        }
    }

    /**
     * @param string $clientIp
     */
    private function updateSiteVisit($clientIp)
    {
        $date = date('Y-m-d');

        if ($this->userVisitsRepository->checkUserVisit($clientIp, $date)) {
            return;
        }

        $this->userVisitsRepository->insertUserVisit($clientIp, $date);
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