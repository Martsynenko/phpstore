<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 10.05.2018
 * Time: 23:56
 */

namespace App\Services\UrlDataService\UrlDataBuilder;

use App\Repository\PhpUrlsRepository;
use App\Services\UrlDataService\UrlDataBuilder\TypeUrlData\ArticleType;
use App\Services\UrlDataService\UrlDataBuilder\TypeUrlData\TypeInterface;
use Symfony\Component\HttpFoundation\Request;

class UrlDataBuilder
{
    const KEY_URL = 'url';
    const KEY_URL_ID = 'url_id';
    const KEY_SECTION = 'section';
    const KEY_SECTION_ID = 'section_id';
    const KEY_ARTICLE_ID = 'article_id';

    const ADMIN_SUB_URL = '/wde-master/admin/';
    const HOME_URL = '/';

    /** @var  TypeInterface[] $typesUrlData */
    private $typesUrlData = [];

    /**
     * @var PhpUrlsRepository $phpUrlsRepository
     */
    protected $phpUrlsRepository;

    public function __construct(
        PhpUrlsRepository $phpUrlsRepository,
        ArticleType $articleType
    )
    {
        $this->phpUrlsRepository = $phpUrlsRepository;

        $this->typesUrlData = [
            $articleType
        ];
    }

    public function prepareUrlDataArray(Request $request)
    {
        $urlDataArray = [];

        $url = $this->prepareUrlForUrlData($request);
        $urlData = $this->phpUrlsRepository->getUrlDataByUrl($url);

        if (!empty($urlData)) {
            $urlData = current($urlData);
        }
        $urlDataArray[self::KEY_URL] = $url;
        $urlDataArray[self::KEY_URL_ID] = $urlData['id'] ? $urlData['id'] : null;
        $urlDataArray[self::KEY_SECTION_ID] = $urlData['sectionId'] ? $urlData['sectionId'] : null;
        $urlDataArray[self::KEY_SECTION] = $urlData['sectionId'] ? $urlData['section'] : null;

        $typeUrlData = $this->defineTypeUrlData($urlDataArray[self::KEY_SECTION]);
        if ($typeUrlData instanceof TypeInterface) {
            $urlDataArray = $typeUrlData->prepareUrlDataArray($urlDataArray);
        }

        return $urlDataArray;
    }

    private function prepareUrlForUrlData(Request $request)
    {
        $baseUrl = $request->getRequestUri();
        $adminUrl = strrpos($baseUrl, self::ADMIN_SUB_URL);

        if ($adminUrl === false) {
            if ($baseUrl === self::HOME_URL) {
                return $baseUrl;
            }
            return ltrim($baseUrl, '/');
        }

        $url = substr($baseUrl, 18);

        return $url;
    }

    /**
     * @param $section
     * @return TypeInterface|null
     */
    private function defineTypeUrlData($section)
    {
        /** @var TypeInterface $type */
        foreach ($this->typesUrlData as $type) {
            if ($type->defineType($section)) {
                return $type;
            }
        }

        return null;
    }
}