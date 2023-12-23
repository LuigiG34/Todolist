<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\Cache;

class TaskController extends AbstractController
{

    #[Route('/tasks', name:'task_list', methods: ['GET'])]
    #[Cache(smaxage: "60")]
    public function listAction(ManagerRegistry $managerRegistry, Request $request): Response
    {
        $response = $this->render('task/list.html.twig', ['tasks' => $managerRegistry->getRepository('App\Entity\Task')->findAll()]);

        $response->setEtag(md5($response->getContent()));
        $response->setPublic();
    
        if ($response->isNotModified($request)) {
            return $response;
        }
    
        return $response;
    }


    #[Route('/tasks/create', name:'task_create', methods: ['GET', 'POST'])]
    public function createAction(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $managerRegistry->getManager();
            $task->setUser($this->getUser());
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }


    #[Route('/tasks/{id}/edit', name:'task_edit', methods: ['GET', 'POST'])]
    public function editAction(Task $task, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $managerRegistry->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }


    #[Route('/tasks/{id}/toggle', name:'task_toggle', methods: ['GET', 'POST'])]
    public function toggleTaskAction(Task $task, ManagerRegistry $managerRegistry): Response
    {
        $task->toggle(!$task->isDone());
        $managerRegistry->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }


    #[Route('/tasks/{id}/delete', name:'task_delete', methods: ['GET', 'POST'])]
    #[IsGranted('TASK_DELETE', subject: 'task')]
    public function deleteTaskAction(Task $task, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
    
}
