<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    #[Route('/', name:'app_homepage', methods: ['GET'])]
    public function indexAction(): Response
    {
        return $this->render('default/index.html.twig');
    }
}
