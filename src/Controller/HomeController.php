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
    public function Home(Request $request, Security $security): Response
    {
        // Check if user is authenticated
        if ($security->getUser() !== null) {
            // Check if user has the role of admin
            if (in_array('ROLE_ADMIN', $security->getUser()->getRoles(), true)) {
                // Redirect admin to the admin home page
                return $this->redirectToRoute('Home2');
            } else {
                // Render the regular home page
                return $this->render('Index.html.twig', []);
            }
        } else {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
    }
    #[Route('/HomeAdmin', name: 'Home2')]
    public function HomeUser(Request $request): Response
    {
        
        return $this->render('base.html.twig', [
            
        ]);
    }

    
}
