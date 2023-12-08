<?php

namespace Tests\AppBundle\Controller;

use Tests\AppBundle\CustomTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerFunctionalTest extends CustomTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testFunctionalListTasks()
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_list'));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFunctionalCreateTask()
    {
        $em = $this->client->getContainer()->get('doctrine')->getManager();
        $em->beginTransaction();

        try{

            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_create'));
            $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

            $form = $crawler->selectButton('Ajouter')->form();
            $form['task[title]'] = 'Title';
            $form['task[content]'] = 'Content for the task.'; 

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

    public function testFunctionalEditTask()
    {
        $em = $this->client->getContainer()->get('doctrine')->getManager();
        $em->beginTransaction();

        try {

            $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_edit', ['id' => 34]));
            $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

            $form = $crawler->selectButton('Modifier')->form();
            $form['task[title]'] = 'New Title';
            $form['task[content]'] = 'New content for the task.'; 
            
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

    public function testFunctionalToggleTask()
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_toggle', ['id' => 34]));
        $crawler = $this->client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
    
    public function testFunctionalDeleteTask()
    {
        $em = $this->client->getContainer()->get('doctrine')->getManager();
        $em->beginTransaction();

        try {

            $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_delete', ['id' => 34]));
            $crawler = $this->client->followRedirect();
            $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        } catch (\Exception $e) {

            $em->rollback();
            throw $e;
            
        }
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
