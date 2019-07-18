<?php

namespace App\Form;

use App\Entity\Application;
use App\Entity\Job;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserType extends AbstractType
{

    /**
     * @var PasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UrlGeneratorInterface $generator)
    {

        $this->passwordEncoder = $passwordEncoder;
        $this->generator = $generator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
        ;

        $builder->setAction($this->generator->generate('admin_users_add'));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            /** @var User $user */
            $user = $event->getData();

            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, 'secret')
            );
            $user->setRoles(
                ['ROLE_ADMIN']
            );
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
