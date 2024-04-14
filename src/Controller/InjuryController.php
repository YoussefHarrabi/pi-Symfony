<?php

namespace App\Controller;

use App\Entity\Injury;
use App\Form\InjuryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\InjuryRestCalculator;

#[Route('/injury')]
class InjuryController extends AbstractController
{
    #[Route('/', name: 'app_injury_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $injuries = $entityManager
            ->getRepository(Injury::class)
            ->findAll();

           

        return $this->render('injury/index.html.twig', [
            'injuries' => $injuries,
        ]);
    }

    #[Route('/new', name: 'app_injury_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $injury = new Injury();
        $form = $this->createForm(InjuryType::class, $injury);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($injury);
            $entityManager->flush();

            return $this->redirectToRoute('app_injury_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('injury/new.html.twig', [
            'injury' => $injury,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_injury_show', methods: ['GET'])]
    public function show(Injury $injury): Response
    {
        return $this->render('injury/show.html.twig', [
            'injury' => $injury,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_injury_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Injury $injury, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InjuryType::class, $injury);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_injury_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('injury/edit.html.twig', [
            'injury' => $injury,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_injury_delete', methods: ['POST'])]
    public function delete(Request $request, Injury $injury, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$injury->getId(), $request->request->get('_token'))) {
            $entityManager->remove($injury);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_injury_index', [], Response::HTTP_SEE_OTHER);
    }



}
