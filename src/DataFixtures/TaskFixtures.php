<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();


        $task = new Task;
        $task->setTitle("Unique Title for tests");
        $task->setContent($faker->paragraph());
        $manager->persist($task);

        // Anonyme Task's
        for($i = 1; $i < 9; $i++) {
            $task = new Task;
            $task->setTitle($faker->sentence());
            $task->setContent($faker->paragraph());
            
            $manager->persist($task);
        }


        // User Task's
        for($i = 1; $i < 9; $i++) {
            $task = new Task;
            $task->setTitle($faker->sentence());
            $task->setContent($faker->paragraph());

            // Get a referenced user
            $user = $this->getReference('user-' . rand(1, 8));
            $task->setUser($user);
            
            $manager->persist($task);
        }

        $manager->flush();
    }


    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
