<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Task;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class YourFixture implements FixtureInterface
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
