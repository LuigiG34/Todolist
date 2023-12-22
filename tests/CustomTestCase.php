<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class CustomTestCase extends WebTestCase
{
    protected $client = null;
    protected $userRepository = null;
    protected $urlGenerator = null;
    protected $user = null;
    protected $userEdit = null;
    protected $taskRepository = null;
    protected $task = null;

    protected function setUp(): void
    {
        if (null === $this->client) {
            $this->client = static::createClient();
        }

        $this->userRepository = $this->client->getContainer()->get('doctrine')->getRepository('App\Entity\User');
        $this->user = $this->userRepository->findOneBy(['email' => 'luigi@admin.fr']);
        $this->userEdit = $this->userRepository->findOneBy(['email' => 'luigi@user.fr']);

        $this->taskRepository = $this->client->getContainer()->get('doctrine')->getRepository('App\Entity\Task');
        $this->task = $this->taskRepository->findOneBy(['title' => 'Unique Title for tests']);

        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->client->disableReboot();
        
        $this->client->loginUser($this->user);
    }

    protected function logOut()
    {
        $this->client->getContainer()->get('security.token_storage')->setToken(null);
    }
}
