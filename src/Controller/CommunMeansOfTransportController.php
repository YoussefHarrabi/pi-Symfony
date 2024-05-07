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
use App\Service\PdfService;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;


class CommunMeansOfTransportController extends AbstractController
{
    #[Route('/commun/means/of/transport', name: 'app_commun_means_of_transport')]
    public function index(Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        return $this->render('commun_means_of_transport/index.html.twig', [
            'controller_name' => 'CommunMeansOfTransportController',
        ]);
    }

    


     #[Route('/commun_means_of_transport/form', name: 'CommunMeansOfTransport_add')]
 	   public function AddCommunMeansOfTransport(ManagerRegistry $doctrine, Request $request,Security $security): Response
 	   {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
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
	    public function show(CommunMeansOfTransportRepository $rep,Security $security): Response
	    {
            if ($security->getUser() === null) {
                // Redirect anonymous users to the login page
                return $this->redirectToRoute('app_login');
            }

	        $CommunMeansOfTransports = $rep->findAll();
	        return $this->render('commun_means_of_transport/list.html.twig', ['CommunMeansOfTransports'=>$CommunMeansOfTransports]);

          

	    }


        #[Route('/commun_means_of_transport/form2', name: 'CommunMeansOfTransport_show2')]
        public function form2(CommunMeansOfTransportRepository $rep,Security $security): Response
        {
            if ($security->getUser() === null) {
                // Redirect anonymous users to the login page
                return $this->redirectToRoute('app_login');
            }
            $CommunMeansOfTransports = $rep->findAll();
            return $this->render('commun_means_of_transport/form2.html.twig', ['CommunMeansOfTransports'=>$CommunMeansOfTransports]);
        }


		#[Route('/CommunMeansOfTransport/update/{id}', name: 'CommunMeansOfTransport_update')]
     public function UpdateCommunMeansOfTransport(ManagerRegistry $doctrine, Request $request, CommunMeansOfTransportRepository $rep, $id,Security $security): Response
     {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
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
     public function deleteCommunMeansOfTransport($id, CommunMeansOfTransportRepository $rep, ManagerRegistry $doctrine,Security $security): Response
     {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
         $em= $doctrine->getManager();
         $CommunMeansOfTransport= $rep->find($id);
         $em->remove($CommunMeansOfTransport);
         $em->flush();
         return $this-> redirectToRoute('CommunMeansOfTransport_show');
     }


     
 

#[Route('/commun_means_of_transport/pdf', name: 'CommunMeansOfTransport_pdf')]
       public function generatePdfList(CommunMeansOfTransportRepository $rep, PdfService $pdf,Security $security): Response
{
    if ($security->getUser() === null) {
        // Redirect anonymous users to the login page
        return $this->redirectToRoute('app_login');
    }
    $CommunMeansOfTransports = $rep->findAll();

    $html = $this->renderView('commun_means_of_transport/pdf.html.twig', [
        'CommunMeansOfTransports' => $CommunMeansOfTransports,
    ]);

    $pdfContent = $pdf->generatePdfFile($html);

    $response = new Response($pdfContent);

    $disposition = $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        'CommunMeansOfTransport_list.pdf'
    );
    $response->headers->set('Content-Disposition', $disposition);

    return $response;
}

     



}