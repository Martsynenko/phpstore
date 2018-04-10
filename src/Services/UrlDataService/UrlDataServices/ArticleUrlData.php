<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 09.04.2018
 * Time: 15:21
 */

namespace App\Services\UrlDataService\UrlDataServices;

use App\Repository\PhpUrlsArticlesRepository;
use App\Repository\PhpUrlsRepository;
use App\Services\UrlDataService\UrlData;
use App\Services\UrlDataService\UrlDataServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleUrlData extends UrlData implements UrlDataServiceInterface
{
    /**
     * @var PhpUrlsArticlesRepository $phpUrlsArticlesRepository
     */
    private $phpUrlsArticlesRepository;

    /**
     * @var int $articleId
     */
    private $articleId;

    public function __construct(
        RequestStack $request,
        PhpUrlsRepository $phpUrlsRepository,
        PhpUrlsArticlesRepository $phpUrlsArticlesRepository
    ) {
        parent::__construct($request, $phpUrlsRepository);

        $this->phpUrlsArticlesRepository = $phpUrlsArticlesRepository;

        $this->prepareUrlData();
    }

    public function prepareUrlData()
    {
        $articleId = $this->phpUrlsArticlesRepository->getArticleIdByUrlId($this->urlId);
        if (!empty($articleId)) {
            $articleId = array_shift($articleId)['articleId'];
        } else {
            $articleId = null;
        }

        $this->articleId = $articleId;
    }

    /**
     * @return int|null
     */
    public function getArticleId()
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