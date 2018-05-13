<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 08.05.2018
 * Time: 21:41
 */

namespace App\Services\VisitService\VisitTypes;

use App\Services\UrlDataService\UrlData;

interface VisitTypeInterface
{
    public function defineTypeServiceBySection($section);

    public function updateVisit(UrlData $urlData);
}