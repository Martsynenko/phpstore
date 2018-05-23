<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 21.05.2018
 * Time: 21:43
 */

namespace App\Controller\PhpStore;

use App\Services\UrlDataService\UrlData;
use App\Services\VisitService\VisitService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AbstractController extends Controller
{
    /** @var  UrlData $urlData */
    protected $urlData;

    public function __construct(
        UrlData $urlData,
        VisitService $visitService
    )
    {
        $this->urlData = $urlData;
        $visitService->updateVisit($urlData);
    }
}