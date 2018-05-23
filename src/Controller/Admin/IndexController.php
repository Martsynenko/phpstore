<?php

namespace App\Controller\Admin;

use App\Repository\PhpUserVisitsRepository;
use App\ValidationHelper\AuthValidationHelper;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class IndexController extends Controller
{
    /**
     * @Route("wde-master/admin/index/", name="admin-home")
     * @param SessionInterface $session
     * @param AuthValidationHelper $authValidationHelper
     * @param PhpUserVisitsRepository $userVisitRepository
     * @return Response
     */
    public function index(
        SessionInterface $session,
        AuthValidationHelper $authValidationHelper,
        PhpUserVisitsRepository $userVisitRepository
    )
    {
        $sessionKey = $session->get(LoginController::SESSION_SESSION_KEY);
        $userId = $session->get(LoginController::SESSION_USER_ID);

        if (!$authValidationHelper->checkAuthUser($userId, $sessionKey)) {
            return $this->redirectToRoute('admin-login');
        }

        $titleDiagram = date('F Y');

        $month = date('m');

        $visitData = $userVisitRepository->getUserVisitsByDate($month);
        $visitDays = array_column($visitData, 'day');

        $resultVisit = [];

        for($i = 1; $i <= 31; $i++) {
            $keyVisitDay = array_search($i, $visitDays);
            if ($keyVisitDay === false) {
                $resultVisit[$i]['day'] = $i;
                $resultVisit[$i]['count'] = 0;
            } else {
                $resultVisit[$i] = $visitData[$keyVisitDay];
            }
        }

        $countVisits = array_column($resultVisit, 'count');

        return $this->render('admin/index/index.html.twig', [
            'titleDiagram' => $titleDiagram,
            'dataDiagram' => json_encode($countVisits)
        ]);
    }
}
