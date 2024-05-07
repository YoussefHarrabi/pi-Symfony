<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\StationformType;
use App\Entity\Station;
use App\Repository\StationRepository;
use Symfony\Component\Security\Core\Security;



class StationController extends AbstractController
{
    #[Route('/station', name: 'app_station')]
    public function index(Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        return $this->render('station/index.html.twig', [
            'controller_name' => 'StationController',
        ]);
    }
    #[Route('/station/stationform', name: 'Station_add')]
    public function AddStation(ManagerRegistry $doctrine, Request $request,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $Station =new Station();
        $stationform=$this->createForm(StationformType::class,$Station);
        $stationform->handleRequest($request);
        if($stationform->isSubmitted()&& $stationform->isValid()){
            $em= $doctrine->getManager();
            $em->persist($Station);
            $em->flush();
            return $this-> redirectToRoute('Station_show');
        }
        return $this->render('station/stationform.html.twig',[
            'formA'=>$stationform->createView(),
        ]);
    }

    #[Route('/station/show', name: 'Station_show')]
    public function show(StationRepository $rep,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $Stations = $rep->findAll();
        return $this->render('station/stationlist.html.twig', ['Stations'=>$Stations]);
    }






    #[Route('/station/show2', name: 'Station_show2')]
    public function show2(StationRepository $rep, Request $request,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        // Récupérer le terme de recherche et les paramètres de tri depuis la requête
        $searchTerm = $request->query->get('search', '');
        $sortField = $request->query->get('sortField', 'id'); // Tri par id par défaut
        $sortOrder = $request->query->get('sortOrder', 'ASC'); // Ordre croissant par défaut
    
        // Obtenir les stations en fonction de la recherche et du tri
        $stations = $rep->findBySearchCriteriaAndSort($searchTerm, $sortField, $sortOrder);
    
        // Vérifier si la requête est une requête AJAX
        if ($request->isXmlHttpRequest()) {
            return $this->render('station/search.html.twig', [
                'Stations' => $stations,
                'searchTerm' => $searchTerm,
                'currentSortField' => $sortField,
                'currentSortOrder' => $sortOrder,
            ]);
        }
    
        // Rendre la page entière pour les requêtes non-AJAX
        return $this->render('station/show2.html.twig', [
            'Stations' => $stations,
            'searchTerm' => $searchTerm,
            'currentSortField' => $sortField,
            'currentSortOrder' => $sortOrder,
        ]);
    }
    



                                                   
    
    #[Route('/Station/update/{id}', name: 'Station_update')]
    public function UpdateStation(ManagerRegistry $doctrine, Request $request, StationRepository $rep, $id,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        } 
       $Station = $rep->find($id);
       $stationform=$this->createForm(StationformType::class,$Station);
       $stationform->handleRequest($request);
       if($stationform->isSubmitted()){
           $em= $doctrine->getManager();
           $em->persist($Station);
           $em->flush();
           return $this-> redirectToRoute('Station_show');
       }
       return $this->render('station/stationform.html.twig',[
           'formA'=>$stationform->createView(),
       ]);
    }
    #[Route('/Station/delete/{id}', name: 'Station_delete')]
     public function deleteStation($id, StationRepository $rep, ManagerRegistry $doctrine,Security $security): Response
     {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
         $em= $doctrine->getManager();
         $Station= $rep->find($id);
         $em->remove($Station);
         $em->flush();
         return $this-> redirectToRoute('Station_show');
     }

}
