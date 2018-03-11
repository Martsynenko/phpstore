<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17.02.2018
 * Time: 12:52
 */

namespace App\PageHelper;

class PageHelper implements PageHelperInterface
{
    /**
     * @param int $page
     * @param int $limit
     * @return int
     */
    public function getOffset($page, $limit)
    {
        $offset = ($page - 1) * $limit;

        return $offset;
    }
}