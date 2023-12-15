<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Anonyme Task's
        for($i = 1; $i < 9; $i++) {
            $task = new Task;
            $task->setTitle($faker->sentence());
            $task->setContent($faker->paragraph());
            
            $manager->persist($task);
        }

        $manager->flush();
    }
}
