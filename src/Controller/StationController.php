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



class StationController extends AbstractController
{
    #[Route('/station', name: 'app_station')]
    public function index(): Response
    {
        return $this->render('station/index.html.twig', [
            'controller_name' => 'StationController',
        ]);
    }
    #[Route('/station/stationform', name: 'Station_add')]
    public function AddStation(ManagerRegistry $doctrine, Request $request): Response
    {
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
    public function show(StationRepository $rep): Response
    {
        $Stations = $rep->findAll();
        return $this->render('station/stationlist.html.twig', ['Stations'=>$Stations]);
    }
    #[Route('/Station/update/{id}', name: 'Station_update')]
    public function UpdateStation(ManagerRegistry $doctrine, Request $request, StationRepository $rep, $id): Response
    {
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
     public function deleteStation($id, StationRepository $rep, ManagerRegistry $doctrine): Response
     {
         $em= $doctrine->getManager();
         $Station= $rep->find($id);
         $em->remove($Station);
         $em->flush();
         return $this-> redirectToRoute('Station_show');
     }

}
