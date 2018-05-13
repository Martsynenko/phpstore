<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 09.04.2018
 * Time: 13:25
 */

namespace App\Services\UrlDataService;

use App\Services\UrlDataService\UrlDataBuilder\UrlDataBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class UrlData implements UrlDataInterface
{
    const ADMIN_SUB_URL = '/wde-master/admin/';
    const HOME_URL = '/';

    /**
     * @var Request $request
     */
    private $request;

    /**
     * @var string $url
     */
    private $url;

    /**
     * @var int $urlId
     */
    private $urlId;

    /**
     * @var string $section
     */
    private $section;

    /**
     * @var int $sectionId
     */
    private $sectionId;

    /**
     * @var int $articleId
     */
    private $articleId;

    public function __construct(
        RequestStack $requestStack,
        UrlDataBuilder $urlDataBuilder,
        $urlDataArray = []
    ) {
        $request = $requestStack->getCurrentRequest();
        $this->request = $request;

        if (empty($urlDataArray)) {
            $urlDataArray = $urlDataBuilder->prepareUrlDataArray($request);
        }

        $this->prepareUrlData($urlDataArray);
    }

    public function prepareUrlData($urlDataArray)
    {
        $this->url = isset($urlDataArray[UrlDataBuilder::KEY_URL]) ? $urlDataArray[UrlDataBuilder::KEY_URL] : null;
        $this->urlId = isset($urlDataArray[UrlDataBuilder::KEY_URL_ID]) ? $urlDataArray[UrlDataBuilder::KEY_URL_ID] : null;
        $this->section = isset($urlDataArray[UrlDataBuilder::KEY_SECTION]) ? $urlDataArray[UrlDataBuilder::KEY_SECTION] : null;
        $this->sectionId = isset($urlDataArray[UrlDataBuilder::KEY_SECTION_ID]) ? $urlDataArray[UrlDataBuilder::KEY_SECTION_ID] : null;
        $this->articleId = isset($urlDataArray[UrlDataBuilder::KEY_ARTICLE_ID]) ? $urlDataArray[UrlDataBuilder::KEY_ARTICLE_ID] : null;
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
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * @param int $articleId
     */
    public function setArticleId(int $articleId)
    {
        $this->articleId = $articleId;
    }
}