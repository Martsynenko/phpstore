<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 08.05.2018
 * Time: 21:41
 */

namespace App\Services\VisitService\Types;

interface TypeInterface
{
    public function defineTypeService();

    public function updateVisit();
}