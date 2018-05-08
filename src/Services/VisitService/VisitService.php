<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 08.05.2018
 * Time: 21:24
 */

namespace App\Services\VisitService;

use App\Repository\UserVisitsRepository;
use App\Services\VisitService\Types\ArticleType;
use App\Services\VisitService\Types\TypeInterface;
use App\Services\UrlDataService\UrlData;

class VisitService
{
    /** @var  TypeInterface[] $typeServices */
    private $typeServices;

    /** @var  UrlData $urlData */
    private $urlData;

    /** @var  UserVisitsRepository $userVisitsRepository */
    private $userVisitsRepository;

    public function __construct(
        UrlData $urlData,
        UserVisitsRepository $userVisitsRepository,
        ArticleType $articleType
    )
    {
        $this->urlData = $urlData;
        $this->userVisitsRepository = $userVisitsRepository;

        $this->typeServices = [
            $articleType
        ];
    }

    public function updateVisit()
    {
        $this->updateSiteVisit();

        $typeService = $this->defineTypeService();
        if ($typeService instanceof TypeInterface) {
            $typeService->updateVisit();
        }
    }

    private function updateSiteVisit()
    {
        $clientIp = $this->urlData->getRequest()->getClientIp();
        $date = date('Y-m-d');

        if ($this->userVisitsRepository->checkUserVisit($clientIp, $date)) {
            return;
        }

        $this->userVisitsRepository->insertUserVisit($clientIp, $date);
    }

    private function defineTypeService()
    {
        return [];
    }
}