<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    #[Route('/', name: 'app_to_do_list')]
    public function index(TaskRepository $taskRepository): Response
    {

        $tasks = $taskRepository->findAll();

        return $this->render('to_do_list/index.html.twig', [
            'controller_name' => 'ToDoListController',
            'tasks' => $tasks
        ]);
    }

    #[Route('/create', name: 'create_task', methods: ["POST"])]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $name = $request->get('title');

        if (empty($name)) {
            return $this->redirectToRoute('app_to_do_list');
        }

        $entityManager = $doctrine->getManager();
        $task = new Task();

        $task->setName($name);
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('app_to_do_list');
    }

    #[Route('/switch-status/{id}', name: 'switch-status')]
    public function switch_status(int $id, TaskRepository $taskRepository, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $task = $taskRepository->find($id);

        if (empty($task)) {
            return $this->render('exception/index.html.twig');
        }

        $task->setStatus(!$task->isStatus());

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('app_to_do_list');
    }

    #[Route('/delete/{id}', name: 'delete_task')]
    public function delete(int $id, TaskRepository $taskRepository, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $task = $taskRepository->find($id);

        if (empty($task)) {
            return $this->render('exception/index.html.twig');
        }

        $entityManager->remove($task);

        $entityManager->flush();

        return $this->redirectToRoute('app_to_do_list');
    }
}
