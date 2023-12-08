<?php

namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class CustomTestCase extends WebTestCase
{
    protected $client = null;
    protected $userRepository = null;
    protected $urlGenerator = null;
    protected $user = null;

    protected function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get('doctrine')->getRepository('AppBundle:User');
        $this->user = $this->userRepository->findOneBy(['email' => 'luigi@example.fr']);
        $this->urlGenerator = $this->client->getContainer()->get('router');

        $this->logIn();
    }

    protected function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewallName = 'main';

        $token = new UsernamePasswordToken($this->user, null, $firewallName, $this->user->getRoles());
        $session->set('_security_'.$firewallName, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    protected function logOut()
    {
        $session = $this->client->getContainer()->get('session');
        $session->invalidate();

        $this->client->request('GET', '/logout');
    }

    protected function tearDown()
    {
        $this->logOut();
        parent::tearDown();
    }
}
