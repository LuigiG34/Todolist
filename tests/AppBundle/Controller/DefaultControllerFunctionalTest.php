<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\CustomTestCase;

class DefaultControllerFunctionnalTest extends CustomTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testFunctionalIndex()
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('homepage'));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
