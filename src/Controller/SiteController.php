<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function home(): Response
    {
        return $this->render('site/home.html.twig');
    }

    /**
     * @Route("/about", name="app_about")
     *
     * @return Response
     */
    public function about(): Response
    {
        return $this->render('site/about.html.twig');
    }



}
