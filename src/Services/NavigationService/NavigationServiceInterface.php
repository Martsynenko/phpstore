<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17.02.2018
 * Time: 12:50
 */

namespace App\Services\NavigationService;

interface NavigationServiceInterface
{
    /**
     * @param int $page
     * @param int $limit
     * @return int
     */
    public function getOffset($page, $limit);
}