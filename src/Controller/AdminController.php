<?php

namespace App\Controller;

use App\Repository\AppointmentRepository;
use App\Repository\JobRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 *
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_dashboard")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'current' => 'admin_dashboard'
        ]);
    }

    /**
     * @Route("/myaccount", name="admin_account")
     */
    public function myAccount(Request $request)
    {
        return $this->render('admin/account/show.html.twig', [
            'current' => 'admin_account'
        ]);
    }



}
