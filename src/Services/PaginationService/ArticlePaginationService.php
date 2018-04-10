<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 29.03.2018
 * Time: 18:43
 */

namespace App\Services\PaginationService;

use App\Controller\PhpStore\IndexController;
use App\Repository\PhpArticlesRepository;
use App\Services\CounterService\CounterService;

class ArticlePaginationService implements PaginationServiceInterface
{
    const COUNT_PAGINATION_PAGES = 5;

    const KEY_PAGINATION_SHOW = 'show';
    const KEY_PAGINATION_PAGES = 'pages';

    /** @var  int $countArticles */
    protected $countArticles;

    /** @var  PhpArticlesRepository $phpArticlesRepository */
    protected $phpArticlesRepository;

    public function __construct(
        PhpArticlesRepository $phpArticlesRepository
    )
    {
        $this->phpArticlesRepository = $phpArticlesRepository;
    }

    /**
     * @param int $limit
     * @return bool
     */
    public function showPagination($limit)
    {
        $countArticles = $this->phpArticlesRepository->getCountArticles();
        $countArticles = (int)array_shift($countArticles)[CounterService::KEY_COUNT_ARTICLES];

        $this->countArticles = $countArticles;

        if ($countArticles <= $limit) {
            return false;
        }

        return true;
    }

    /**
     * @param int $page
     * @param string $routeName
     * @return array
     */
    public function getArticlePagination($page, $routeName)
    {
        $pagination = [];

        if (!$this->showPagination(IndexController::HOME_ARTICLES_LIMIT)) {
            $pagination[self::KEY_PAGINATION_SHOW] = false;
            return $pagination;
        }

        $maxNumberPage = $this->countArticles / IndexController::HOME_ARTICLES_LIMIT;
        if (is_float($maxNumberPage)) {
            $maxNumberPage = (int)($maxNumberPage + 1);
        }

        $firstPage = 1;
        if ($page > 3) {
            $firstPage = $page - 2;
        }

        if ($page + 2 > $maxNumberPage) {
            $diff = ($page + 2) - $maxNumberPage;
            $firstPage = $firstPage - $diff;
        }

        $activePage = false;
        for ($i = $firstPage; $i < $firstPage + self::COUNT_PAGINATION_PAGES; $i++) {
            if ($i <= $maxNumberPage) {
                if ($page == $i) {
                    $activePage = true;
                }
                $pagination[self::KEY_PAGINATION_PAGES][] = [
                    'page' => $i,
                    'url' => 'page' . $i,
                    'active' => $activePage
                ];
                $activePage = false;
            }
        }

        $pagination['routeName'] = $routeName;
        $pagination[self::KEY_PAGINATION_SHOW] = true;

        return $pagination;
    }
}