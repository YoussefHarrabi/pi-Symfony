<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateurs;
use App\Form\UserRegistrationFormType;
use App\Form\LoginFormType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;



class UserController extends AbstractController
{


    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security)
    {
        // Check if the user is already authenticated
        if ($security->getUser() !== null) {
            // User is already authenticated, redirect to the home page
            return $this->redirectToRoute('ListeUsers'); // Replace 'home_page' with the name of your home page route
        }

        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Render the login form
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);


    }



    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(Request $request, LogoutSuccessHandlerInterface $logoutSuccessHandler): Response
    {
        // Clear the user's session
        $this->get('session')->invalidate();

        // Optionally perform additional tasks (e.g., logging)

        // Redirect to a specific page after logout
        return $logoutSuccessHandler->onLogoutSuccess($request);
    }

    // #[Route('/login', name: 'app_login')]
    // public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    // {

    //     dump($request->request->all());

    //     // If user is already authenticated, redirect to homepage or other appropriate route
    //     if ($this->getUser()) {
    //         return $this->redirectToRoute('ListeUsers'); // Change 'dashboard' to the appropriate route name
    //     }

    //     // Create a form instance using your LoginFormType
    //     $form = $this->createForm(LoginFormType::class);

    //     // Handle form submission
    //     $form->handleRequest($request);


    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Retrieve the data from the form
    //         $formData = $form->getData();

    //         // Make sure the name matches the form field name
    //         $email = $formData['email']; // Ensure the email is retrieved correctly

    //         // You should check if $email is not null before proceeding
    //         if ($email !== null) {
    //             $userRepository = $this->getDoctrine()->getRepository(Utilisateurs::class);
    //             $user = $userRepository->findOneBy(['email' => $email]);

    //             if ($user) {
    //                 // Authentication logic
    //                 $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
    //                 $this->get('security.token_storage')->setToken($token);

    //                 // Dispatch the login event
    //                 $event = new InteractiveLoginEvent($request, $token);
    //                 $this->get('event_dispatcher')->dispatch($event);

    //                 // Redirect the user after successful authentication
    //                 return $this->redirectToRoute('ListeUsers');
    //             } else {
    //                 // Handle authentication failure, e.g., display error message
    //                 $this->addFlash('error', 'Invalid email or password.');
    //             }
    //         } else {
    //             // Handle the case where email is null
    //             $this->addFlash('error', 'Email field is required.');
    //         }
    //     }

    //     // Get the login error if there is one
    //     $error = $authenticationUtils->getLastAuthenticationError();

    //     // Last username entered by the user
    //     $lastUsername = $authenticationUtils->getLastUsername();

    //     return $this->render('GestionUser/login.html.twig', [
    //         'form' => $form->createView(),
    //         'last_username' => $lastUsername,
    //         'error' => $error,
    //     ]);
    // }        



    /**#[Route('/', name: 'login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils, UserPasswordEncoderInterface $passwordEncoder): Response
    {
          // Check if user is authenticated
          if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('Home'); // Redirect to dashboard or home page
        }
        // Create the login form
        $form = $this->createForm(LoginFormType::class);

        // Get the error (if any) from the authentication attempt
        $error = $authenticationUtils->getLastAuthenticationError();

        // Get the last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // If it's a POST request, handle form submission
        if ($request->isMethod('POST')) {
            // Handle form submission and authentication here
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Retrieve the submitted data
                $formData = $form->getData();

                // Get the email and password from the form data
                $email = $formData['email'];
                $password = $formData['password'];

                // Find the user by email in the database
                $user = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['email' => $email]);

                // If user found and password matches
                if ($user && password_verify($password, $user->getMotDePasse())) {
                    // Authentication successful, redirect to index page
                    // You may need to handle this part according to your application's logic
                    return $this->redirectToRoute('Home');
                } else {
                    // Authentication failed, add flash message or handle accordingly
                    $this->addFlash('error', 'Invalid email or password');
                }
            }
        }

        // Render the login form
        return $this->render('GestionUser/login.html.twig', [
            'form' => $form->createView(),
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
      */

    #[Route('/register', name: 'user_register')]
    public function register(Request $request): Response
    {
        $user = new Utilisateurs();
        $user->setRole('admin'); // Set the role to 'admin'

        $form = $this->createForm(UserRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password
            $hashedPassword = password_hash($user->getMotDePasse(), PASSWORD_DEFAULT);
            $user->setMotDePasse($hashedPassword);

            // Handle form submission, e.g., saving to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to a success page or any other action
            return $this->redirectToRoute('app_login');
        }

        return $this->render('GestionUser/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/Liste', name: 'ListeUsers')]
    public function list(Request $request): Response
    {

        // Fetch user data from the database
        $userRepository = $this->getDoctrine()->getRepository(Utilisateurs::class);
        $users = $userRepository->findAll();

        // Render the template with user data
        return $this->render('GestionUser/TableUsers.html.twig', [
            'users' => $users,
        ]);
    }
}
