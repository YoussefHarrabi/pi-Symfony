<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request; // Import the Request class
use App\Form\LoginFormType;
use Symfony\Component\Security\Core\Security;


class HomeController extends AbstractController
{

    #[Route('/Home', name: 'Home')]
    public function Home(Request $request,Security $security): Response
    {
        if ($security->getUser() !== null) {
        
        return $this->render('Index.html.twig', [
            
        ]);}
        else {
            return $this->redirectToRoute('app_login');}
    }
    #[Route('/HomeUser', name: 'Home2')]
    public function HomeUser(Request $request): Response
    {
        
        return $this->render('base2.html.twig', [
            
        ]);
    }
}
