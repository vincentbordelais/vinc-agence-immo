<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    protected $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $hash = $this->encoder->encodePassword($admin, 'demo');
        $admin->setEmail('admin@gmail.com')
            ->setPassword($hash)
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        for ($u = 0; $u < 5; ++$u) {
            $user = new User();
            $hash = $this->encoder->encodePassword($user, 'demo');
            $user->setEmail("user{$u}@gmail.com")
                ->setPassword($hash);
            $manager->persist(($user));
        };

        $manager->flush();
    }
}
