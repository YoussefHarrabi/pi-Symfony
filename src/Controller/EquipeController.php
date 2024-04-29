<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Repository\WorkRepository;

use App\Repository\EquipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormError;

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
    public function add(Request $request, ValidatorInterface $validator): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Validate the 'nbrPersonne' field
            $nbrPersonne = $equipe->getNbrPersonne();
            if (!is_numeric($nbrPersonne)) {
                $form->get('nbrPersonne')->addError(new FormError('Le nombre de personnes doit être un nombre.'));
            }

            if ($form->isValid()) {
                // Persist and flush the entity
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($equipe);


                $entityManager->flush();

                // Redirect after successful save
                return $this->redirectToRoute('app_equipe');
            }
        }

        // Render the form with validation errors or initial form
        return $this->render('equipe/equipe_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/equipe/{id}/edit', name: 'equipe_edit')]
    public function edit(Request $request, Equipe $equipe, WorkRepository $workRepository): Response
    {
        $form = $this->createForm(EquipeType::class, $equipe);
        $works = $workRepository->findAll();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'L\'équipe a été mise à jour avec succès.');

            return $this->redirectToRoute('app_equipe'); // Redirect to the equipe index route
        }

        return $this->render('equipe/edite.html.twig', [
            'equipe' => $equipe,
            'form' => $form->createView(),
            'works' => $works,
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
