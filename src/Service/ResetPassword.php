<?php


namespace App\Service;


use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPassword
{
    /**
     * @var FormInterface
     */
    private $form;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;

        $this->form = $this->formFactory->create(ResetPasswordType::class);
    }

    public function getForm()
    {
        return $this->form;
    }

    public function resetPassword(Request $request, User $user)
    {

        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {

            list($oldPassword, $password, $passwordConfirmation) = array_values($this->form->getData());

            if (!$this->userPasswordEncoder->isPasswordValid($user, $oldPassword)) {

                $this->form->get('oldPassword')->addError(new FormError('Old password is invalid'));

                return $this->form;
            }

            if ($password !== $passwordConfirmation) {

                $this->form->get('passwordConfirmation')->addError(new FormError("Password confirmation don't match"));

                return $this->form;
            }


            $user->setPassword($this->userPasswordEncoder->encodePassword($user,$password));
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->form;
        }


        return null;

    }
}