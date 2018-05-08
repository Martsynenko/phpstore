<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 06.05.2018
 * Time: 19:11
 */

namespace App\Controller\PhpStore;

use App\Entity\PhpCommentsUsers;
use App\Entity\PhpUsersVerification;
use App\Repository\PhpCommentsUsersRepository;
use App\Repository\PhpUsersVerificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends Controller
{
    const STATUS_YES = 'yes';
    const STATUS_NO = 'no';

    /**
     * @Route("/verification/", name="verificationUser")
     * @param Request $request
     * @param PhpUsersVerificationRepository $phpUsersVerificationRepository
     * @param PhpCommentsUsersRepository $phpCommentsUsersRepository
     * @return Response
     */
    public function verificationUser(
        Request $request,
        PhpUsersVerificationRepository $phpUsersVerificationRepository,
        PhpCommentsUsersRepository $phpCommentsUsersRepository
    )
    {
        $dataHash = $request->get('hash');
        $verificationData = $phpUsersVerificationRepository->getVerificationDataByHash($dataHash);
        if ($verificationData) {
            /** @var PhpUsersVerification $userVerification */
            $userVerification = current($verificationData);
            $userId = $userVerification->getUserId();
            /** @var PhpCommentsUsers $commentUser */
            $commentUser = $phpCommentsUsersRepository->find($userId);
            $userName = $commentUser->getName();
            $pageReferer = $userVerification->getPageReferer();

            $phpCommentsUsersRepository->updateUserStatusVerification($userId, self::STATUS_YES);
            $phpUsersVerificationRepository->deleteUserVerification($userVerification);
            return $this->render('phpstore/registration/verification.html.twig', [
                'userName' => $userName,
                'pageReferer' => '/' . ltrim($pageReferer, '/')
            ]);
        } else {
            return $this->render('phpstore/404.html.twig');
        }
    }
}