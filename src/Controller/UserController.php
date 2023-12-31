<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpKernel\Attribute\Cache;

class UserController extends AbstractController
{

    #[Route('/users', name:'user_list', methods: ['GET'])]
    #[Cache(smaxage: "60")]
    public function listAction(ManagerRegistry $managerRegistry, Request $request): Response
    {
        $response = $this->render('user/list.html.twig', ['users' => $managerRegistry->getRepository('App\Entity\User')->findAll()]);
    
        // Générer le etag
        $response->setEtag(md5($response->getContent()));
        $response->setPublic(); // Rendre reponse public + caché et partager le cache
    
        // Si pas modifié
        if ($response->isNotModified($request)) {
            return $response;
        }
    
        return $response;
    }


    #[Route('/users/create', name:'user_create', methods: ['GET', 'POST'])]
    public function createAction(Request $request, UserPasswordHasherInterface $passwordEncoder, ManagerRegistry $managerRegistry): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $managerRegistry->getManager();
            $password = $passwordEncoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }


    #[Route('/users/{id}/edit', name:'user_edit', methods: ['GET', 'POST'])]
    public function editAction(User $user, Request $request, UserPasswordHasherInterface $passwordEncoder, ManagerRegistry $managerRegistry): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $managerRegistry->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
