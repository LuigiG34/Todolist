<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/', name:'app_homepage')]
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
}
