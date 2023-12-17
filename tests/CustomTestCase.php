<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class CustomTestCase extends WebTestCase
{
    protected $client = null;
    protected $userRepository = null;
    protected $urlGenerator = null;
    protected $user = null;

    protected function setUp(): void
    {
        if (null === $this->client) {
            $this->client = static::createClient();
        }
        $this->userRepository = $this->client->getContainer()->get('doctrine')->getRepository('App\Entity\User');
        $this->user = $this->userRepository->findOneBy(['email' => 'luigi@example.fr']);
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->client->disableReboot();
        
        $this->client->loginUser($this->user);
    }

    protected function logOut()
    {
        $session = $this->client->getContainer()->get('session');
        $session->invalidate();
    }
}
