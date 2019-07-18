<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResetPasswordType extends AbstractType
{

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {

        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'attr' => [
                    'class' => "form-control form-control-user",
                    'placeholder' => "Old Password"
                ],
                'label' => false,
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => "form-control form-control-user",
                    'placeholder' => "Password"
                ],
                'label' => false
            ])
            ->add('passwordConfirmation', PasswordType::class, [
                'attr' => [
                    'class' => "form-control form-control-user",
                    'placeholder' => "Password Confirmation"
                ],
                'label' => false
            ]);

        $builder
            ->setAction($this->urlGenerator->generate('admin_users_reset_password'));
    }
}