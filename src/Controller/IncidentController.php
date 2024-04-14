<?php

namespace App\Controller;

use App\Entity\Incident;
use App\Form\IncidentType;
use App\Repository\IncidentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/incident')]
class IncidentController extends AbstractController
{
    #[Route('/', name: 'app_incident_index', methods: ['GET'])]
    public function index(IncidentRepository $incidentRepository): Response
    {
        return $this->render('incident/index.html.twig', [
            'incidents' => $incidentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_incident_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $incident = new Incident();
        $form = $this->createForm(IncidentType::class, $incident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($incident);
            $entityManager->flush();

            return $this->redirectToRoute('app_incident_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('incident/new.html.twig', [
            'incident' => $incident,
            'form' => $form,
        ]);
    }

    #[Route('/{incidentid}', name: 'app_incident_show', methods: ['GET'])]
    public function show(Incident $incident): Response
    {
        return $this->render('incident/show.html.twig', [
            'incident' => $incident,
        ]);
    }

    #[Route('/edit/{incidentid}', name: 'app_incident_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Incident $incident, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(IncidentType::class, $incident);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Flush the changes to the database
        $entityManager->flush();

        // Redirect back to the incident page
        return $this->redirectToRoute('app_incident_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('incident/edit.html.twig', [
        'incident' => $incident,
        'form' => $form,
    ]);
}
    #[Route('/delete/{incidentid}', name: 'app_incident_delete', methods: ['POST'])]
    public function delete(Request $request, Incident $incident, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$incident->getIncidentid(), $request->request->get('_token'))) {
            $entityManager->remove($incident);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_incident_index', [], Response::HTTP_SEE_OTHER);
    
    }
    #[Route("/delete-incident/{id}", name:"delete_incident", methods:["POST"])]
    public function deleteIncident(Incident $incident, EntityManagerInterface $entityManager): JsonResponse
    {
        // Delete the incident from the database
        $entityManager->remove($incident);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Incident deleted successfully'], JsonResponse::HTTP_OK);
    }

   
}

