<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Repository\EquipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EquipeController extends AbstractController
{
    #[Route('/equipe', name: 'app_equipe')]
    public function index(EquipeRepository $equipeRepository): Response
    {
        $equipes = $equipeRepository->findAll();
        return $this->render('equipe/index.html.twig', [
            'equipes' => $equipes,
        ]);
    }
    #[Route('/equipe/add', name: 'equipe_add')]
    public function add(Request $request): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($equipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_equipe');
        }

        return $this->render('equipe/equipe_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/equipe/{id}/edit', name: 'equipe_edit')]
    public function edit(Request $request, Equipe $equipe): Response
    {
        // Create the edit form for the existing Equipe entity
        $form = $this->createForm(EquipeType::class, $equipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Process the form submission (update the Equipe entity)
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            // Redirect to a success route or show a success message
            return $this->redirectToRoute('app_work'); // Example: Redirect to work index
        }

        // Render the edit form template with the form view and the Equipe entity
        return $this->render('equipe/edite.html.twig', [
            'equipe' => $equipe, // Pass the Equipe entity to the template
            'form' => $form->createView(), // Pass the form view to the template
        ]);
    }

    #[Route('/equipe/delete/{id}', name: 'equipe_delete', methods: ['POST'])]
    public function delete(Request $request, Equipe $equipe): Response
    {
        if ($this->isCsrfTokenValid('delete' . $equipe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($equipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_equipe');
    }
}
