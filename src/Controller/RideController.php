<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RideFormType;
use App\Entity\Ride;
use App\Repository\RideRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Types\FloatType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;

class RideController extends AbstractController
{
    #[Route('/ride/show', name: 'ride_show')]
    public function show(Request $request,RideRepository $rep,PaginatorInterface $paginator, EntityManagerInterface $entityManager,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        // Retrieve all rides
        $rides = $rep->findAll();
        
        // Calculate average rating for each driver
        $driverRatings = [];
        foreach ($rides as $ride) {
            $driver = $ride->getDriver();
            $rating = $ride->getRating();
            // Skip rides with null ratings
            if ($rating === null) {
                continue;
            }
            if (!isset($driverRatings[$driver])) {
                $driverRatings[$driver] = ['total' => 0, 'count' => 0];
            }
            $driverRatings[$driver]['total'] += $rating;
            $driverRatings[$driver]['count']++;
        }
        
        // Calculate average rating for each driver
        $driverAverages = [];
        foreach ($driverRatings as $driver => $ratingData) {
            $averageRating = $ratingData['total'] / $ratingData['count'];
            $driverAverages[$driver] = $averageRating;
        }
        
        // Find driver with the highest average rating
        $highestRatedDriver = null;
        $highestRating = 0;
        foreach ($driverAverages as $driver => $averageRating) {
            if ($averageRating > $highestRating) {
                $highestRating = $averageRating;
                $highestRatedDriver = $driver;
            }
        }
        $rides = $paginator->paginate(
            $rides, /* query NOT result */
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('ride/ridelist.html.twig', [
            'rides' => $rides,
            'highestRatedDriver' => $highestRatedDriver,
            'highestRating' => $highestRating
        ]);
    }


    #[Route('/ride/showback', name: 'ride_showback')]
    public function showback(RideRepository $rep,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        // Retrieve all rides
        $rides = $rep->findAll();
        
        // Use QueryBuilder to group drivers with more than 5 reports
        $driversWithTotalReportsGreaterThanFive = $rep->createQueryBuilder('r')
            ->select('r.driver, COUNT(r.reports) AS totalReports')
            ->groupBy('r.driver')
            ->having('totalReports > 5')
            ->getQuery()
            ->getResult();

        return $this->render('ride/backlist.html.twig', [
            'rides' => $rides,
            'driversWithTotalReportsGreaterThanFive' => $driversWithTotalReportsGreaterThanFive,
        ]);
    }

    #[Route('/ride/form', name: 'ride_add')]
    public function AddARide(Request $request,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $ride = new Ride();
        $form = $this->createForm(RideFormType::class, $ride);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ride);
            $entityManager->flush();

            return $this->redirectToRoute('ride_show');
        }

        $errors = [];
        if ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
        }

        return $this->render('ride/form.html.twig', [
            'formA' => $form->createView(),
            'errors' => $errors
        ]);
    }

    #[Route('/ride/update/{id}', name: 'ride_update')]
    public function UpdateRide(ManagerRegistry $doctrine, Request $request, RideRepository $rep, $id,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $ride = $rep->find($id);
        $form = $this->createForm(RideFormType::class, $ride);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($ride);
            $em->flush();
            return $this->redirectToRoute('ride_show');
        }

        $errors = [];
        if ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
        }

        return $this->render('ride/form.html.twig', [
            'formA' => $form->createView(),
            'errors' => $errors
        ]);
    }

    #[Route('/ride/delete/{id}', name: 'ride_delete')]
    public function deleteRide($id, RideRepository $rep, ManagerRegistry $doctrine,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $em = $doctrine->getManager();
        $ride = $rep->find($id);
        $em->remove($ride);
        $em->flush();
        return $this->redirectToRoute('ride_show');
    }

    #[Route('/ride/{id}/rate', name: 'ride_rate')]
    public function rateRide(Request $request, $id, RideRepository $rideRepository, EntityManagerInterface $entityManager,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $ride = $rideRepository->find($id);
    
        if (!$ride) {
            throw $this->createNotFoundException('The ride does not exist');
        }
    
        if ($request->isMethod('POST')) {
            $rating = $request->request->get('rating');
            $report = $request->request->get('report'); // Retrieve the selected report
    
            // Set the rating to the ride entity
            $ride->setRating($rating);
    
            // Add the report to the ride's reports array
            $reports = $ride->getReports() ?? []; // Get existing reports or initialize an empty array
            $reports[] = $report; // Add the new report
            $ride->setReports($reports); // Set the updated reports array
    
            // Persist the changes to the database
            $entityManager->flush();
    
            // Redirect to the list page
            return $this->redirectToRoute('ride_show');
        }
    
        return $this->render('ride/rate.html.twig', [
            'ride' => $ride,
        ]);
    }
}
