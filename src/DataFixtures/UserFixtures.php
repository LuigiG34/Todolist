<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $user = new User;
        $user->setUsername("LuigiAdmin");
        $user->setEmail("luigi@admin.fr");
        $user->setPassword($this->passwordHasher->hashPassword($user, "motdepasse"));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);


        $user = new User;
        $user->setUsername("LuigiUser");
        $user->setEmail("luigi@user.fr");
        $user->setPassword($this->passwordHasher->hashPassword($user, "motdepasse"));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);


        for($i = 1; $i < 9; $i++) {
            $users = new User;
            $users->setUsername($faker->userName());
            $users->setEmail($faker->email());
            $users->setPassword($this->passwordHasher->hashPassword($users, $faker->password()));
            $users->setRoles(['ROLE_USER']);
            
             // Set reference for later use
            $this->addReference('user-'.$i, $user);

            $manager->persist($users);
        }

        $manager->flush();
    }
}
