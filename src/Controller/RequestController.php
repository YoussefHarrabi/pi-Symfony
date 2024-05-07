<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RequestFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use App\Entity\RequestRide;
use App\Repository\RequestRideRepository;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Security;
class RequestController extends AbstractController
{
    
    #[Route('/request/show', name: 'request_show')]
    public function show(RequestRideRepository $rep,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        

        $requests = $rep->findAll();
        return $this->render('request/requestlist.html.twig', ['requests' => $requests]);
    }
    #[Route('/request/showback', name: 'request_showback')]
    public function showback(RequestRideRepository $rep,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $requests = $rep->findAll();
        return $this->render('request/showback.html.twig', ['requests' => $requests]);
    }
    #[Route('request/form', name: 'request_add', methods: ['GET', 'POST'])]
public function AddARequest(Request $request, EntityManagerInterface $entityManager,Security $security): Response
{
    if ($security->getUser() === null) {
        // Redirect anonymous users to the login page
        return $this->redirectToRoute('app_login');
    }
    $RequestRide = new RequestRide();
    $form = $this->createForm(RequestFormType::class, $RequestRide);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Persist the entity only if the form is submitted and valid
        $entityManager->persist($RequestRide);
        $entityManager->flush();

       

        return $this->redirectToRoute('request_show');
    }

    return $this->render('request/form.html.twig', [
        'formA' => $form->createView(),
        'request' => $request,
    ]);
}

    





    #[Route('/request/update/{id}', name: 'request_update')]
    public function UpdateRequest(ManagerRegistry $doctrine, Request $request, RequestRideRepository $rep, $id): Response
    {
        $RequestRide = $rep->find($id);
        $form = $this->createForm(RequestFormType::class, $RequestRide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($RequestRide);
            $em->flush();
            return $this->redirectToRoute('request_show');
        }

        // Add custom error handling to display validation errors
        $errors = [];
        if ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
        }

        return $this->render('request/form.html.twig', [
            'formA' => $form->createView(),
            'errors' => $errors
        ]);
    }


    #[Route('/request/delete/{id}', name: 'request_delete')]
    public function deleteRequest($id, RequestRideRepository $rep, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $RequestRide = $rep->find($id);
        $em->remove($RequestRide);
        $em->flush();
        return $this->redirectToRoute('request_show');
    }
    #[Route('request/stats', name: 'statistics')]
    public function statistics(RequestRideRepository $requestRideRepository): Response
{
    $statistics = $requestRideRepository->getStatisticsByStartLocation();
    dump($statistics); // Debugging

    return $this->render('request/stats.html.twig', [
        'statistics' => $statistics,
        'labels' => array_column($statistics, 'startlocation'),
        'data' => array_column($statistics, 'totalRequests')
    ]);
}

}
