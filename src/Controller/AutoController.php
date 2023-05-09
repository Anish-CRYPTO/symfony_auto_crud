<?php

namespace App\Controller;

use App\Entity\AUTO;
use App\Form\InsertType;
use App\Repository\AUTORepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutoController extends AbstractController
{
    #[Route('/auto', name: 'auto')]
    public function index(AUTORepository $repository ): Response
    {
        $auto = $repository->findAll();
        return $this->render('auto/index.html.twig', ["autos" =>$auto]);
    }

    #[Route('/auto/insert', name: 'insert')]
    public function insert(Request $request, ManagerRegistry $doctrine): Response
    {
        $insert = new AUTO();
        $form = $this->createForm(InsertType::class,$insert);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $doctrine->getManager();
            $insert = $form->getData();
            $entityManager->persist($insert);
            $entityManager->flush();
            $this->addFlash('success', 'a car has been added');
            return $this->redirectToRoute('auto');


        }
        return $this->renderForm('auto/form.html.twig', ["form" =>$form]);

    }

    #[Route('/auto/update/{id}', name:'update')]
    public function update(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $auto = $entityManager->getRepository(AUTO::class)->find($id);
        $form = $this->createForm(InsertType::class,$auto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $entityManager->flush();

            $this->addFlash('success', 'a car has been added');
            return $this->redirectToRoute('auto');

        }
        return $this->renderForm('auto/form.html.twig', ["form" =>$form]);

    }
    #[Route('/auto/delete/{id}', name: 'delete')]
    public function delete(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $auto = $entityManager->getRepository(AUTO::class)->find($id);
        $entityManager->remove($auto);
        $entityManager->flush();

        $this->addFlash('danger', "a car has been removed");
        return $this->redirectToRoute('auto');
    }

    #[Route('/auto/detail/{id}', name: 'detail')]
    public function detail(ManagerRegistry $registry, int $id):Response
    {
        $car = $registry->getRepository(AUTO::class)->find($id);
        return $this->render('auto/index.html.twig', ["autos" => $car]);
    }
}


