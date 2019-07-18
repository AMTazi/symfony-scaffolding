<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixture implements FixtureGroupInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct();

        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        $this->createMany(10, 'main_users', function ($i) use($manager) {

            $user = new User();

            $user->setName($this->faker->name);

            $user->setEmail(sprintf('user%d@example.com', $i));

            $user->setPassword($this->passwordEncoder->encodePassword($user, 'secret'));

            if ($this->faker->boolean()) {

                $user->setRoles(['ROLE_ADMIN']);
            }

            $user->setCreatedAt(new \DateTime());

            $user->setUpdatedAt(new \DateTime());

            $manager->persist($user);

        });

        $manager->flush();
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['group0'];
    }
}
