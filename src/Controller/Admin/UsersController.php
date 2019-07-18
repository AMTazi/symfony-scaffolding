<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Service\ResetPassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/users")
 * Class UsersController
 * @package App\Controller\Admin
 */
class UsersController extends AbstractController
{


    /**
     * @Route("/", name="dashboard_admins", methods={"GET"})
     */
    public function admins()
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        return $this->render('admin/users/index.html.twig', [
            'current' => "dashboard_admins",
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/new", name="admin_users_add", methods={"POST"})
     *
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_admins');
        }

        return $this->render('admin/users/index.html.twig', [
            'current' => "dashboard_admins",
            'form' => $form->createView()
        ]);

    }


    /**
     * @Route("/myaccount/update", name="admin_myaccount_update", methods={"PUT"})
     */
    public function update(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        if ($request->request->has('name'))
            $user->setName($request->request->get('name'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();


        return $this->redirectToRoute('admin_account');

    }

    /**
     * @Route("/rest-password", name="admin_users_reset_password", methods={"POST"})
     * @param Request $request
     * @param ResetPassword $resetPassword
     * @return Response
     */
    public function resetPassword(Request $request, ResetPassword $resetPassword)
    {

        $form = $resetPassword->resetPassword($request, $this->getUser());

        if (!($form instanceof FormInterface)) {
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('admin/account/show.html.twig', [
            'password_reset' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="admin_users_delete", methods={"DELETE"})
     */
    public function delete(User $user, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_admins');
    }


}
