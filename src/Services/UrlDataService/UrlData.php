<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 09.04.2018
 * Time: 13:25
 */

namespace App\Services\UrlDataService;

use App\Entity\PhpUrls;
use App\Repository\PhpUrlsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class UrlData
{
    const KEY_URL = 'url';
    const KEY_URL_ID = 'url_id';
    const KEY_SECTION = 'section';
    const KEY_SECTION_ID = 'section_id';

    const ADMIN_SUB_URL = '/wde-master/admin/';

    /**
     * @var Request $request
     */
    protected $request;

    /**
     * @var PhpUrlsRepository $phpUrlsRepository
     */
    protected $phpUrlsRepository;

    /**
     * @var string $url
     */
    protected $url;

    /**
     * @var int $urlId
     */
    protected $urlId;

    /**
     * @var string $section
     */
    protected $section;

    /**
     * @var int $sectionId
     */
    protected $sectionId;

    public function __construct(
        RequestStack $request,
        PhpUrlsRepository $phpUrlsRepository
    )
    {
        $this->request = $request->getCurrentRequest();
        $this->phpUrlsRepository = $phpUrlsRepository;

        $urlDataArray = $this->prepareBaseUrlDataArray();
        $this->fillBaseUrlData($urlDataArray);
    }

    private function fillBaseUrlData($urlDataArray)
    {
        $this->url = $urlDataArray[self::KEY_URL];
        $this->urlId = $urlDataArray[self::KEY_URL_ID];
        $this->section = $urlDataArray[self::KEY_SECTION];
        $this->sectionId = $urlDataArray[self::KEY_SECTION_ID];
    }

    protected function prepareBaseUrlDataArray()
    {
        $urlDataArray = [];

        $url = $this->prepareUrlForUrlData();
        $section = $this->getSectionByUrl($url);
        $baseUrlData = $this->phpUrlsRepository->getUrlDataByUrl($url);

        $urlDataArray[self::KEY_URL] = $url;
        $urlDataArray[self::KEY_SECTION] = $section;

        if (!empty($baseUrlData)) {
            /** @var PhpUrls $baseUrl */
            $baseUrl = current($baseUrlData);
        }
        /** @var PhpUrls $baseUrl */
        $urlDataArray[self::KEY_URL_ID] = $baseUrlData ? $baseUrl->getId() : null;
        $urlDataArray[self::KEY_SECTION_ID] = $baseUrlData ? $baseUrl->getSectionId() : null;

        return $urlDataArray;
    }

    private function prepareUrlForUrlData()
    {
        $baseUrl = $this->request->getRequestUri();
        $adminUrl = strrpos($baseUrl, self::ADMIN_SUB_URL);

        if ($adminUrl === false) {
            return ltrim($baseUrl, '/');
        }

        $url = substr($baseUrl, 18);

        return $url;
    }

    /**
     * @param string $url
     * @return bool|string
     */
    private function getSectionByUrl($url)
    {
        $pos = strpos($url, '/');
        $section = substr($url, 0, $pos);
        return $section;
    }

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return int|null
     */
    public function getUrlId()
    {
        return $this->urlId;
    }

    /**
     * @param int $urlId
     */
    public function setUrlId(int $urlId)
    {
        $this->urlId = $urlId;
    }

    /**
     * @return string|null
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param string $section
     */
    public function setSection(string $section)
    {
        $this->section = $section;
    }

    /**
     * @return int|null
     */
    public function getSectionId()
    {
        return $this->sectionId;
    }

    /**
     * @param int $sectionId
     */
    public function setSectionId(int $sectionId)
    {
        $this->sectionId = $sectionId;
    }
}