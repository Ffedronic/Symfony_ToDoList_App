<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    #[Route('/', name: 'app_to_do_list')]
    public function index(): Response
    {
        return $this->render('to_do_list/index.html.twig', [
            'controller_name' => 'ToDoListController',
        ]);
    }

    #[Route('/create', name: 'create_task', methods: ["POST"])]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $name = $request->get('title');
        
        if (empty($name)) {
            return $this->redirectToRoute('app_to_do_list');
        }

        $entityManager = $doctrine -> getManager();
        $task = new Task();

        $task->setName($name);
        $entityManager->persist($task);
        $entityManager->flush();
     
        return $this->redirectToRoute('app_to_do_list');

    }

    #[Route('/switch-status/{id}', name: 'switch-status')]
    public function switch_status(int $id): Response
    {
        exit("switch status of a task!" . $id);
    }

    #[Route('/delete/{id}', name: 'delete_task')]
    public function delete(int $id): Response
    {
        exit("deleted task!" . $id);
    }
}
