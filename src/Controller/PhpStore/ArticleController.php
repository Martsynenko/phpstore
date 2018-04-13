<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 27.03.2018
 * Time: 22:54
 */

namespace App\Controller\PhpStore;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ArticleController extends Controller
{
    /**
     * @Route("{articleUrl}", requirements={"articleUrl"="article/.+/"}, name="article")
     * @return Response
     */
    public function index()
    {
        return $this->render('phpstore/article/article.html.twig');
    }

    /**
     * @Route("/article/load-comments/")
     * @return JsonResponse
     */
    public function loadComments()
    {
        return new JsonResponse(['test']);
    }
}

