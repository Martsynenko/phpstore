<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18.05.2018
 * Time: 13:38
 */

namespace App\Controller\Admin;

use App\ValidationHelper\AuthValidationHelper;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CommentsController extends AbstractController
{
    /**
     * CommentsController constructor.
     * @param AuthValidationHelper $authValidationHelper
     * @param SessionInterface $sessionInterface
     */
    public function __construct(
        AuthValidationHelper $authValidationHelper,
        SessionInterface $sessionInterface
    )
    {
        parent::__construct($authValidationHelper, $sessionInterface);
    }

    /**
     * @Route("/wde-master/admin/comments/", name="admin-comments")
     */
    public function comments()
    {
        return $this->render('admin/comments/comments.html.twig');
    }
}