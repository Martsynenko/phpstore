<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17.02.2018
 * Time: 12:50
 */

namespace App\PageHelper;

interface PageHelperInterface
{
    public function getOffset($page, $limit);
}