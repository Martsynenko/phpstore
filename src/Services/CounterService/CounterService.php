<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28.03.2018
 * Time: 21:47
 */

namespace App\Services\CounterService;

use App\Repository\PhpArticlesCommentsRepository;
use App\Repository\PhpArticlesRepository;

class CounterService
{
    const KEY_COUNT_ARTICLES = 'countArticles';
    const KEY_COUNT = 'count';
    const KEY_TITLE = 'title';

    /** @var  PhpArticlesRepository $phpArticlesRepository */
    private $phpArticlesRepository;

    /** @var  PhpArticlesCommentsRepository $phpArticlesCommentsRepository */
    private $phpArticlesCommentsRepository;

    public function __construct(
        PhpArticlesRepository $phpArticlesRepository,
        PhpArticlesCommentsRepository $phpArticlesCommentsRepository
    )
    {
        $this->phpArticlesRepository = $phpArticlesRepository;
        $this->phpArticlesCommentsRepository = $phpArticlesCommentsRepository;
    }

    /**
     * @return array
     */
    public function getBreadcrumbCountArticles()
    {
        $countArticles = $this->phpArticlesRepository->getCountArticles();

        if (empty($countArticles)) {
            return [];
        }

        $countArticles = array_shift($countArticles)[self::KEY_COUNT_ARTICLES];

        switch ($countArticles) {
            case 1:
                $title = 'публикация';
                break;
            case 2:
            case 3:
            case 4:
                $title = 'публикации';
                break;
            default:
                $title = 'публикаций';
                break;
        }

        return [self::KEY_COUNT => $countArticles, self::KEY_TITLE => $title];
    }

    /**
     * @param $articleId
     * @return array
     */
    public function getCountArticleComments($articleId)
    {
        $count = $this->phpArticlesCommentsRepository->getCountCommentsByArticleId($articleId);

        return $count;
    }
}