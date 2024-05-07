<?php

namespace App\Controller;

use App\Entity\Work;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;




class ClientController extends AbstractController
{
    #[Route('/client/works', name: 'client_works')]
    public function index(Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $worksRepository = $entityManager->getRepository(Work::class);
        $works = $worksRepository->findAll();
   // Calculate duration for each work item
   foreach ($works as $work) {
    $startDate = $work->getStartDate();
    $endDate = $work->getEndDate();
    $duration = null;

    if ($startDate && $endDate) {
        $interval = $endDate->diff($startDate);
        $duration = $interval->days; // Calculate difference in days
    }

    // Add the calculated duration to the work item
    $work->duration = $duration; // Add a 'duration' property to the work item
}
        return $this->render('WorkClient/WorkClient.html.twig', [
            'works' => $works,
        ]);
    }

    #[Route("/save_location", name: "save_location", methods: ["POST"])]
    public function saveLocation(Request $request, LoggerInterface $logger,Security $security): Response
    {  if ($security->getUser() === null) {
        // Redirect anonymous users to the login page
        return $this->redirectToRoute('app_login');
    }
        // Récupérer la localisation à partir des données du formulaire
        $userLocation = $request->request->get('userLocation');

        // Vous pouvez ensuite traiter $userLocation selon vos besoins, par exemple, le stocker en base de données

        // Exemple pour le logger pour le moment
        $logger->info('Location saved: ' . $userLocation);

        // Vous pouvez rediriger l'utilisateur vers une autre page ou retourner une réponse JSON
        $response = new JsonResponse([
            'message' => 'Location saved successfully',
            'location' => $userLocation
        ]);

        $renderedView = $this->render('WorkClient/geo.html.twig', [
            'userLocation' => $userLocation
        ]);

        // Créer une réponse combinée
        $combinedResponse = new Response();
        $combinedResponse->setContent($renderedView->getContent() . $response->getContent());

        // Définir le type de contenu comme HTML
        $combinedResponse->headers->set('Content-Type', 'text/html');

        // Retourner la réponse combinée
        return $combinedResponse;
    }

    #[Route('/client/work/{id}', name: 'work_details')]
    public function workDetails($id,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $worksRepository = $entityManager->getRepository(Work::class);
        $work = $worksRepository->find($id);

        if (!$work) {
            throw $this->createNotFoundException('Work not found');
        }

        return $this->render('work_details.html.twig', [
            'work' => $work,
        ]);
    }
}
