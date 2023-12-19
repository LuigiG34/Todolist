<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{

    #[Route('/tasks', name:'task_list')]
    public function listAction(ManagerRegistry $managerRegistry)
    {
        return $this->render('task/list.html.twig', ['tasks' => $managerRegistry->getRepository('App\Entity\Task')->findAll()]);
    }


    #[Route('/tasks/create', name:'task_create')]
    public function createAction(Request $request, ManagerRegistry $managerRegistry)
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


    #[Route('/tasks/{id}/edit', name:'task_edit')]
    public function editAction(Task $task, Request $request, ManagerRegistry $managerRegistry)
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


    #[Route('/tasks/{id}/toggle', name:'task_toggle')]
    public function toggleTaskAction(Task $task, ManagerRegistry $managerRegistry)
    {
        $task->toggle(!$task->isDone());
        $managerRegistry->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }


    #[Route('/tasks/{id}/delete', name:'task_delete')]
    public function deleteTaskAction(Task $task, ManagerRegistry $managerRegistry)
    {
        $em = $managerRegistry->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
    
}
