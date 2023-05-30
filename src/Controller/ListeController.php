<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Entity\Task;
use App\Form\ListeType;
use App\Repository\ListeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/liste')]
class ListeController extends AbstractController
{
    #[Route('/', name: 'app_liste')]
    public function index(ListeRepository $listeRepository): Response
    {
        $listes=$listeRepository->findAll();

        return $this->render('liste/index.html.twig', [
            'listes' => $listes,
        ]);
    }

    #[Route('/my', name: 'private_liste')]
    public function oo(ListeRepository $listeRepository): Response
    {
        $listes=$listeRepository->findAll();

        return $this->render('liste/private.html.twig', [
            'listes' => $listes,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_liste')]
    #[Route('/new', name: 'new_liste')]
    public function create(EntityManagerInterface$manager, Request $request, Liste $liste=null): Response
    {
        $edit =false;

        if($liste){ $edit=true; };
        if(!$edit){ $liste = new Liste();}

        $formListe=$this->createForm(ListeType::class, $liste);
        $formListe->handleRequest($request);
        if($formListe->isSubmitted() && $formListe->isValid()){

            $liste->setAuthor($this->getUser());
            $liste->setCreatedAt(new \DateTime());

            $tasks=$formListe->getData()->getTasks();
            foreach($tasks as $task){
                $addedTask= new Task();
                $addedTask->setListe($liste);
                $addedTask->setContent($task->getContent());
            }


            $manager->persist($liste);
            $manager->flush();

            return $this->redirectToRoute('app_liste');
        }


        return $this->renderForm('liste/new.html.twig', [
            'formListe'=>$formListe
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_liste')]
    public function delete(EntityManagerInterface $manager, Liste $liste): Response
    {
        if($liste){
            $manager->remove($liste);
            $manager->flush();

            return $this->redirectToRoute('app_liste');
        }
    }
}
