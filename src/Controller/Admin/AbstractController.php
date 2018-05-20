<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18.05.2018
 * Time: 13:41
 */

namespace App\Controller\Admin;

use App\Services\AuthorizationService\AuthorizationService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AbstractController extends Controller
{
    /**
     * AbstractController constructor.
     * @param AuthorizationService $authorizationService
     */
    public function __construct(
        AuthorizationService $authorizationService
    ) {
        $authorizationService->checkAuth();
    }
}