<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * LoginController constructor.
     * @param AuthorizationCheckerInterface $checker
     * @param RouterInterface $router
     */
    public function __construct(AuthorizationCheckerInterface $checker, RouterInterface $router)
    {
        $this->checker = $checker;
        $this->router = $router;
    }

    /**
     * @Route("/login", name="auth_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {

        if ($this->checker->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->router->generate('homepage')); // TODO: find a way to redirect from a controller
        }
        // get the auth error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }


    /**
     * @Route("/logout", name="auth_logout")
     */
    public function logout()
    {
        throw new \Exception('Will be intercepted before getting here');
    }
}
