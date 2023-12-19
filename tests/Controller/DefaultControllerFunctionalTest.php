<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\CustomTestCase;

class DefaultControllerFunctionalTest extends CustomTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testFunctionalIndex()
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('app_homepage'));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
