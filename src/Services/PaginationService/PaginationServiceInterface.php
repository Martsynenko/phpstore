<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 29.03.2018
 * Time: 18:43
 */

namespace App\Services\PaginationService;

interface PaginationServiceInterface
{
    /**
     * @param int $limit
     * @return bool
     */
    public function showPagination($limit);
}