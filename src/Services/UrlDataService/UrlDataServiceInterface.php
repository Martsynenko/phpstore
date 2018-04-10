<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 09.04.2018
 * Time: 15:26
 */

namespace App\Services\UrlDataService;

interface UrlDataServiceInterface
{
    /**
     * @return array
     */
    public function prepareUrlData();
}