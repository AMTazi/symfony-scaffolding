<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SetOwnerCommand extends Command
{
    protected static $defaultName = 'app:set-owner';
    /**
     * @var string
     */
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var PasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct( ObjectManager $manager, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
    {
        $this->manager = $manager;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('email', null, InputOption::VALUE_REQUIRED, 'Owner E-mail')
            ->addArgument('password', null, InputOption::VALUE_REQUIRED, 'Owner Password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);


        $email = $input->getArgument('email');

        $password = $input->getArgument('password');


        if (count($this->userRepository->findAll()) > 0) {
            $io->success(sprintf("Owner is already exists!!"));
            return;
        }

        $user = new User();

        $user->setName('Owner');

        $user->setEmail($email);

        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

        $user->setRoles(['ROLE_ADMIN','ROLE_OWNER']);

        $user->setCreatedAt(new \DateTime());

        $user->setUpdatedAt(new \DateTime());

        $this->manager->persist($user);

        $this->manager->flush();

        $io->success(sprintf("Owner with email=%s added successfully",$email));
    }
}
