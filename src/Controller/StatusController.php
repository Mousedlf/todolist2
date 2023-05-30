<?php

namespace App\Controller;

use App\Entity\Check;
use App\Entity\Task;
use App\Repository\CheckRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends AbstractController
{
    #[Route('/status/{id}', name: 'app_check')]
    public function checkTask(Task $task, CheckRepository $checkRepository, EntityManagerInterface $manager): Response
    {
        $user=$this->getUser();
        if($task->isCheckedBy($user)){
            $check=$checkRepository->findOneBy([
                'author'=>$user,
                'task'=>$task
            ]);
            $manager->remove($check);
            $isChecked = false;
        } else {
            $check = new Check();
            $check->setTask($task);
            $check->setAuthor($user);
            $manager->persist($check);
            $isChecked = true;
        }
        $manager->flush();

        $data = [
            'checked'=>$isChecked,
           // 'count'=>$checkRepository->count(['$task'=>$task])
        ];

        return $this->json($data, 200);
    }
}
