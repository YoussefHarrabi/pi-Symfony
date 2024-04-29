<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Work;
use App\Form\AjoutFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // Utilisation de la bonne classe Request
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\WorkRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\twilioo;
use Twilio\Rest\Client;
use Symfony\Component\HttpFoundation\File\File;
use Knp\Component\Pager\PaginatorInterface;






class WorkController extends AbstractController
{
    #[Route('/work', name: 'app_work')]
    public function index(WorkRepository $workRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $worksQuery = $workRepository->findAll(); // Récupère tous les travaux

        $works = $paginator->paginate(
            $worksQuery, // Requête à paginer
            $request->query->getInt('page', 1), // Numéro de page par défaut
            2 // Nombre d'éléments par page
        );

        return $this->render('work/index.html.twig', [
            'works' => $works,
        ]);
    }
    #[Route('/autocomplete-location', name: 'autocomplete_location')]
    public function autocompleteLocation(Request $request, WorkRepository $workRepository): JsonResponse
    {
        $term = $request->query->get('term');
        $locations = $workRepository->findDistinctLocationsStartingWith($term); // Utiliser une méthode appropriée dans le repository

        $suggestions = [];
        foreach ($locations as $location) {
            $suggestions[] = $location['location']; // Adapter selon le nom du champ
        }

        return new JsonResponse($suggestions);
    }
    #[Route('/work/search', name: 'work_search')]
    public function search(Request $request, WorkRepository $workRepository): Response
    {
        $location = $request->query->get('location');

        if ($location) {
            $works = $workRepository->findByLocationStartingWith($location);
        } else {
            $works = $workRepository->findAll();
        }

        return $this->render('work/search_results.html.twig', [
            'works' => $works,
        ]);
    }


    #[Route('/work/ajouter', name: 'work_new')]
    public function new(Request $request, ValidatorInterface $validator): Response
    {
        $work = new Work();
        $form = $this->createForm(AjoutFormType::class, $work);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $startDate = $work->getStartdate();
            $endDate = $work->getEnddate();

            // Vérifie si la date de fin est postérieure à la date de début
            if ($startDate && $endDate && $endDate <= $startDate) {
                $form->get('enddate')->addError(new FormError('La date de fin doit être postérieure à la date de début.'));
                return $this->render('work/new.html.twig', [
                    'form' => $form->createView(),
                ]);
            }


            // Définit automatiquement 'isactive' en fonction de startdate et enddate
            if ($startDate && $endDate) {
                $today = new \DateTime();
                $isActive = $startDate <= $today && $endDate >= $today;
                $work->setIsactive($isActive);
            }
            // Gérer l'upload de l'image
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                // Déplacez le fichier vers le répertoire où vous souhaitez stocker les images
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer les exceptions liées à l'upload (par exemple, écrire dans les logs)
                }

                // Mettez à jour l'entité Work avec le nom de fichier de l'image
                $work->setImage($newFilename);
            }

            // Persiste et flush l'entité Work
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($work);
            $entityManager->flush();


            // Redirige vers une autre route après l'enregistrement réussi
            return $this->redirectToRoute('app_work');
        }

        // Rend le formulaire initialement ou avec les données soumises et les erreurs de validation
        return $this->render('work/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'work_update')]
    public function update(Request $request, Work $work): Response
    {
        $form = $this->createForm(AjoutFormType::class, $work);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                // Déplacez le fichier vers le répertoire où vous souhaitez stocker les images
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer les exceptions liées à l'upload (par exemple, écrire dans les logs)
                }

                // Mettez à jour l'entité Work avec le nom de fichier de l'image
                $work->setImage($newFilename);
            }

            // Persist the updated Work entity
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            // Redirect to a route after successful update
            return $this->redirectToRoute('app_work'); // Example route to redirect after update
        }

        return $this->render('edit.html.twig', [
            'form' => $form->createView(),
            'work' => $work,
        ]);
    }
    #[Route('/work/{id}/delete', name: 'work_delete', methods: ['POST'])]
    public function delete(Request $request, Work $work): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Check if the CSRF token is valid
        if ($this->isCsrfTokenValid('delete' . $work->getWorkid(), $request->request->get('_token'))) {
            // Manually delete associated equipes
            $equipes = $entityManager->getRepository(Equipe::class)->findBy(['work' => $work->getWorkid()]);

            foreach ($equipes as $equipe) {
                $entityManager->remove($equipe);
            }

            // Now remove the work
            $entityManager->remove($work);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_work');
    }

    #[Route('/send-sms', name: 'send_sms')]
    public function sendSms(Request $request, twilioo $twilioService): Response
    {
        try {
            // Retrieve work details from the POST request
            $workLocation = $request->request->get('workLocation');
            $workDescription = $request->request->get('workDescription');
            $workStartDate = $request->request->get('workStartDate');
            $workEndDate = $request->request->get('workEndDate');
            $workDuration = $request->request->get('workDuration');

            // Customize SMS message with work details
            $message = "Work Details:\n";
            $message .= "Location: " . $workLocation . "\n";
            $message .= "Description: " . $workDescription . "\n";
            $message .= "Start Date: " . $workStartDate . "\n";
            $message .= "End Date: " . $workEndDate . "\n";
            $message .= "Duration: " . $workDuration . " day(s)";

            // Replace with the actual phone number of the recipient
            $userPhoneNumber = '+21654854107'; // Replace with actual phone number

            // Send SMS using Twilio service
            $twilioService->sendSms($userPhoneNumber, $message);

            // Redirect back to a specific route after sending SMS
            return $this->redirectToRoute('client_works');
        } catch (\Exception $e) {
            // Handle any exceptions (e.g., log errors, return error response)
            return new Response('Failed to send SMS: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
