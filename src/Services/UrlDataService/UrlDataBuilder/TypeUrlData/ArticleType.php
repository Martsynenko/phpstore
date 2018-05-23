<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12.05.2018
 * Time: 11:03
 */

namespace App\Services\UrlDataService\UrlDataBuilder\TypeUrlData;

use App\Repository\PhpArticlesRepository;
use App\Repository\PhpUrlsArticlesRepository;
use App\Services\UrlDataService\UrlDataBuilder\UrlDataBuilder;
use Symfony\Component\Validator\Constraints\Url;

class ArticleType implements TypeInterface
{
    const TYPE_ARTICLE = 'Article';

    /** @var  PhpUrlsArticlesRepository $phpUrlsArticlesRepository */
    private $phpUrlsArticlesRepository;

    public function __construct(
        PhpUrlsArticlesRepository $phpUrlsArticlesRepository
    ) {
        $this->phpUrlsArticlesRepository = $phpUrlsArticlesRepository;
    }

    /**
     * @param string $section
     * @return bool
     */
    public function defineType($section)
    {
        if ($section === self::TYPE_ARTICLE) {
            return true;
        }

        return false;
    }

    /**
     * @param array $urlDataArray
     * @return array
     */
    public function prepareUrlDataArray($urlDataArray)
    {
        $urlId = $urlDataArray[UrlDataBuilder::KEY_URL_ID];
        $sectionData = $this->phpUrlsArticlesRepository->getArticleIdByUrlId($urlId);
        if ($sectionData) {
            $sectionData = array_shift($sectionData);
            $urlDataArray[UrlDataBuilder::KEY_ARTICLE_ID] = $sectionData[PhpUrlsArticlesRepository::ENTITY_ARTICLE_ID];
            $urlDataArray[UrlDataBuilder::KEY_STATUS] = $sectionData[PhpArticlesRepository::COLUMN_STATUS];
        }

        return $urlDataArray;
    }
}