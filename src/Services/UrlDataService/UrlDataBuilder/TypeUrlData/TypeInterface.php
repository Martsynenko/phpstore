<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12.05.2018
 * Time: 11:07
 */

namespace App\Services\UrlDataService\UrlDataBuilder\TypeUrlData;

interface TypeInterface
{
    /**
     * @param string $section
     * @return mixed bool
     */
    public function defineType($section);

    /**
     * @param array $urlDataArray
     * @return array
     */
    public function prepareUrlDataArray($urlDataArray);
}