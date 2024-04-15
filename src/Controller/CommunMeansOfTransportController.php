<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommunMeansOfTransportType;
use App\Entity\CommunMeansOfTransport;
use App\Repository\CommunMeansOfTransportRepository;

class CommunMeansOfTransportController extends AbstractController
{
    #[Route('/commun/means/of/transport', name: 'app_commun_means_of_transport')]
    public function index(): Response
    {
        return $this->render('commun_means_of_transport/index.html.twig', [
            'controller_name' => 'CommunMeansOfTransportController',
        ]);
    }
     #[Route('/commun_means_of_transport/form', name: 'CommunMeansOfTransport_add')]
 	   public function AddCommunMeansOfTransport(ManagerRegistry $doctrine, Request $request): Response
 	   {
 	       $CommunMeansOfTransport =new CommunMeansOfTransport();
 	       $form=$this->createForm(CommunMeansOfTransportType::class,$CommunMeansOfTransport);
 	       $form->handleRequest($request);
 	       if($form->isSubmitted()&& $form->isValid()){
 	           $em= $doctrine->getManager();
 	           $em->persist($CommunMeansOfTransport);
 	           $em->flush();
          
 	           return $this-> redirectToRoute('CommunMeansOfTransport_show');
 	       }
 	       return $this->render('commun_means_of_transport/form.html.twig',[
 	           'formA'=>$form->createView(),
 	       ]); 
 	   } 
        
        
        #[Route('/commun_means_of_transport/show', name: 'CommunMeansOfTransport_show')]
	    public function show(CommunMeansOfTransportRepository $rep): Response
	    {
	        $CommunMeansOfTransports = $rep->findAll();
	        return $this->render('commun_means_of_transport/list.html.twig', ['CommunMeansOfTransports'=>$CommunMeansOfTransports]);
	    }
		#[Route('/CommunMeansOfTransport/update/{id}', name: 'CommunMeansOfTransport_update')]
     public function UpdateCommunMeansOfTransport(ManagerRegistry $doctrine, Request $request, CommunMeansOfTransportRepository $rep, $id): Response
     {
        $CommunMeansOfTransport = $rep->find($id);
        $form=$this->createForm(CommunMeansOfTransportType::class,$CommunMeansOfTransport);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em= $doctrine->getManager();
            $em->persist($CommunMeansOfTransport);
            $em->flush();
            return $this-> redirectToRoute('CommunMeansOfTransport_show');
        }
        return $this->render('commun_means_of_transport/form.html.twig',[
            'formA'=>$form->createView(),
        ]);
     }
	 #[Route('/CommunMeansOfTransport/delete/{id}', name: 'CommunMeansOfTransport_delete')]
     public function deleteCommunMeansOfTransport($id, CommunMeansOfTransportRepository $rep, ManagerRegistry $doctrine): Response
     {
         $em= $doctrine->getManager();
         $CommunMeansOfTransport= $rep->find($id);
         $em->remove($CommunMeansOfTransport);
         $em->flush();
         return $this-> redirectToRoute('CommunMeansOfTransport_show');
     }
}
