<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserFixtures extends Fixture
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $encoder = $this->container->get('security.password_encoder');

        $user = new User;
        $user->setUsername("Luigi22");
        $user->setEmail("luigi@example.fr");
        $user->setPassword($encoder->encodePassword($user, "motdepasse"));
            
        $manager->persist($user);


        for($i = 1; $i < 9; $i++) {
            $users = new User;
            $users->setUsername($faker->userName());
            $users->setEmail($faker->email());
            $users->setPassword($encoder->encodePassword($users, $faker->password()));
            
            $manager->persist($users);
        }

        $manager->flush();
    }
}
