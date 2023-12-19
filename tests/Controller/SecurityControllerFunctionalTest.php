<?php

namespace App\Tests\Controller;

use App\Tests\CustomTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerFunctionalTest extends CustomTestCase
{
    public function setUp(): void
    {
        if (null === $this->client) {
            $this->client = static::createClient();
        }
        $this->userRepository = $this->client->getContainer()->get('doctrine')->getRepository('App\Entity\User');
        $this->user = $this->userRepository->findOneBy(['email' => 'luigi@example.fr']);
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->client->disableReboot();
    }

    /**
     * @dataProvider credentialsForLoginTest
     */
    public function testFunctionalLogin($username, $password)
    {
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('login'));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = $username;
        $form['password'] = $password; 

        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $baseUrl = $this->urlGenerator->getContext()->getScheme() . '://' . $this->urlGenerator->getContext()->getHost();
        $expectedRedirectUrl = $baseUrl . $this->urlGenerator->generate('app_homepage');


        if ($expectedRedirectUrl === $this->client->getRequest()->getUri()) {    
            $this->assertGreaterThan(0, $crawler->filter('h1')->count());
            $this->assertSame("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !",trim($crawler->filter('h1')->text()));
        } else {
            $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());
        }
    }


    public static function credentialsForLoginTest()
    {
        return [
            ["Luigi22", "motdepasse"],
            ["mauvaisIdentifiant", "mauvaisMotdepasse"]
        ];
    }
}
