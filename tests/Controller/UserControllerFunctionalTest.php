<?php

namespace App\Tests\Controller;

use App\Tests\CustomTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserControllerFunctionalTest extends CustomTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testFunctionalListUsers()
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_list'));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFunctionalCreateUser()
    {
        $em = $this->client->getContainer()->get('doctrine')->getManager();
        $em->beginTransaction();

        try {
            
            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_create'));

            $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    
            $form = $crawler->selectButton('Ajouter')->form();
            $form['user[username]'] = 'Username';
            $form['user[password][first]'] = 'Password';
            $form['user[password][second]'] = 'Password';
            $form['user[email]'] = 'test@test.fr';
    
            $crawler = $this->client->submit($form);
    
            if ($this->client->getResponse()->isRedirect()) {
                $crawler = $this->client->followRedirect();
            }
    
            $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        } catch (\Exception $e) {

            $em->rollback();
            throw $e;
            
        }
    }

    public function testFunctionalEditUser()
    {
        $em = $this->client->getContainer()->get('doctrine')->getManager();
        $em->beginTransaction();

        try{

            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => 12]));

            $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

            $form = $crawler->selectButton('Modifier')->form();
            $form['user[username]'] = 'UpdatedName';
            $form['user[password][first]'] = 'UpdatedPass';
            $form['user[password][second]'] = 'UpdatedPass';
            $form['user[email]'] = 'updated@email.fr';

            $crawler = $this->client->submit($form);

            if ($this->client->getResponse()->isRedirect()) {
                $crawler = $this->client->followRedirect();
            }

            $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        } catch (\Exception $e) {

            $em->rollback();
            throw $e;
        
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
