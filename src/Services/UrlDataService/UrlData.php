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
    const HOME_URL = '/';

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
        unset($this->phpUrlsRepository);
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
        $baseUrlData = $this->phpUrlsRepository->getUrlDataByUrl($url);

        if (!empty($baseUrlData)) {
            $baseUrl = current($baseUrlData);
        }
        $urlDataArray[self::KEY_URL] = $url;
        $urlDataArray[self::KEY_URL_ID] = $baseUrl ? $baseUrl['id'] : null;
        $urlDataArray[self::KEY_SECTION_ID] = $baseUrl ? $baseUrl['sectionId'] : null;
        $urlDataArray[self::KEY_SECTION] = $baseUrl ? $baseUrl['section'] : null;

        return $urlDataArray;
    }

    private function prepareUrlForUrlData()
    {
        $baseUrl = $this->request->getRequestUri();
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

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }
}