<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18.05.2018
 * Time: 13:38
 */

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class CommentsController extends AbstractController
{
    /**
     * @Route("/wde-master/admin/comments/", name="admin-comments")
     * @return Response
     */
    public function comments()
    {
        return $this->render('admin/comments/comments.html.twig');
    }
}